<?php
namespace App\Services\FlightBooking;

use App\Jobs\FlightBookingPendingEmailJob;
use App\Jobs\SendFlightBookingPendingEmailJob;
use App\Models\FlightBooking;
use App\Models\FlightCabin;
use App\Models\User;
use App\Services\Payment\RefundStripePaymentService;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class FlightBookingService{
    protected RefundStripePaymentService $service ;
    public function __construct(RefundStripePaymentService $service){
        $this->service = $service ;
    }

    public function getAllFlightBookings($filters = []){
        return FlightBooking::filter($filters)->with('flightCabin')->paginate(10);
    }
    /**
     * Create a new flight booking with seat assignment and conflict checks
     * @param array $data
     * @return array{data: FlightBooking, message: string, status: string, status_code: int|array{message: string, status: string, status_code: int}}
     */
    public function createBooking(array $data){
        $user = JWTAuth::parseToken()->authenticate();

        // Get the last seat number for the selected cabin to assign next available seat
        $lastSeatNumber  = FlightBooking::pluck('seat_number')->where('flight_cabins_id',$data['flight_cabins_id'])->max('seat_number') ?? 0;
        $flightCabin = FlightCabin::findOrFail($data['flight_cabins_id']);

        // Get the flight associated with cabin
        $newFlight = $flightCabin->flight;

        // Prepare booking data array
        $bookingData = [
            'flight_cabins_id'=>$data['flight_cabins_id'],
            'booking_date'=>now()->format('Y-m-d H:i'),
            'seat_number'=>$lastSeatNumber +1,
            'status'=>'pending',
            'user_id'=> $user->hasRole('customer') ? $user->id : $data['user_id']
        ];

        // Find the user who the booking is for
        $bookingUser = User::findOrFail($bookingData['user_id']);

        // Check for conflicting bookings
        $existingBookings = $bookingUser->upcommingFlightBookings()->with('flightCabin.flight')->get();
        foreach($existingBookings as $booking){
            $existingFlight = $booking->flightCabin->flight;

            $newDeparture = Carbon::parse($newFlight->departure_time);
            $newArrival = Carbon::parse($newFlight->arrival_time);
            $existingDeparture = Carbon::parse($existingFlight->departure_time);
            $existingArrival = Carbon::parse($existingFlight->arrival_time);

            if($newDeparture->between($existingDeparture , $existingArrival)){
                return [
                    'status' => 'error',
                    'message' => 'You already have a flight scheduled during this time period.',
                    'status_code' => 409
                ];
            }

            // Ensure minimum 12 hours between flights
            if($newDeparture->diffInHours($existingArrival) < 12 || $newArrival->diffInHours($existingDeparture) < 12){
                return [
                    'status' => 'error',
                    'message' => 'Minimum 12 hours required between flights. Your existing flight departs at ' .
                                $existingDeparture->format('Y-m-d H:i') . ' and arrives at ' .
                                $existingArrival->format('Y-m-d H:i'),
                    'status_code' => 400
                ];
            }
        }

        // Start database transaction to ensure data consistency
        DB::beginTransaction();
        try{
            $flightBooking = FlightBooking::create($bookingData);
            dispatch(new FlightBookingPendingEmailJob($flightBooking->id));
            $flightCabin->decrement('available_seats');
            DB::commit();
            return [
                'status'=>'success',
                'data'=>$flightBooking,
                'message'=>'Flight Booked Successfully',
                'status_code'=>201
            ];
        }catch(Exception $e){
            DB::rollBack();
            return [
                'status'=>'error',
                'message'=>'failed to create booking '.$e->getMessage(),
                'status_code'=>500
            ];
        }
    }
    /**
     * cancelBooking
     * @param \App\Models\FlightBooking $flightBooking
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return array{data: FlightBooking, status: string|array{message: string, status: string}}
     */
    public function cancelBooking(FlightBooking $flightBooking){
        $message = "No refund required.";
        $user = JWTAuth::parseToken()->authenticate();

        // Check authorization
        $isAuthorized = $user->hasRole('flight_agent') || ($user->hasRole('customer') && $user->id == $flightBooking->user_id);

        if(!$isAuthorized){throw new AuthorizationException('You are not authorized to cancel this booking');}
        if(in_array($flightBooking->status, ['failed', 'complete','cancelled'])){
            return [
                'status'=>'error',
                'message'=>'Booking cannot be cancelled in its current status ('. $flightBooking->status .')',
            ];
        }

        if($flightBooking->status === 'confirmed'){

            // fetch the payment associated with the booking.
            $refundResult = $this->service->refunde($flightBooking);

            // Failed refund money
            if($refundResult['status'] === 'failed'){
                return [
                    'status'=>'error',
                    'message'=>'Refund failed: '.$refundResult['message']
                ];
            }

            $message = 'Refund processed successfully.';
        }

        // Verify booking is in cancelable state (pending or complete) and user is authorized
        DB::beginTransaction();
        try{
            $flightBooking->update(['status'=>'cancelled']);
            $flightBooking->flightCabin->increment('available_seats');
            DB::commit();

            return [
                'status'=>'success',
                'message'=>$message ,
                'data'=>$flightBooking->refresh()
            ];
        }catch(Exception $e){
            DB::rollBack();
            return [
                'status'=>'error',
                'message'=>'Failed to Cancelled Booking '.$e->getMessage()
            ];
        }
    }
    /**
     * Updates a flight booking's cabin class with seat availability management
     * @param array $data
     * @param \App\Models\FlightBooking $flightBooking
     * @return array{data: FlightBooking, status: string|array{message: string, status: string}}
     */
    public function update(array $data , FlightBooking $flightBooking){
        $user = JWTAuth::parseToken()->authenticate();

        // Store the original flight cabin (before update)
        $orginalFlightCabin = $flightBooking->flightCabin;

        // Find the new flight cabin (throws 404 if not found)
        $newFlightCabin = FlightCabin::findOrFail($data['flight_cabins_id']);

        if($user->hasRole('flight_agent') || ($user->hasRole('customer') && $user->id == $flightBooking->user_id)){

            // 1. Increment available seats in the original cabin (freeing up a seat)
            $orginalFlightCabin->increment('available_seats');

            // 2. Decrement available seats in the new cabin (reserving a seat)
            $newFlightCabin->decrement('available_seats');
            $flightBooking->update($data);

            return [
                'status'=>'success',
                'data'=>$flightBooking->refresh()
            ];
        }else{
            return [
                'status'=>'failed',
                'message'=>'You are not authorized to perform this action'
            ];
        }
    }
    /**
     * Summary of deleteBooking
     * @param \App\Models\FlightBooking $flightBooking
     * @return void
     */
    public function deleteBooking(FlightBooking $flightBooking){
        $flightBooking->flightCabin->increment('available_seats');
        $flightBooking->delete();
    }
}
