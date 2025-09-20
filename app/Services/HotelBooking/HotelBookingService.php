<?php
namespace App\Services\HotelBooking;

use App\Jobs\HotelBookingPendingEmailJob;
use App\Models\HotelBooking;
use App\Models\Room;
use App\Services\Payment\RefundStripePaymentService;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class HotelBookingService{
    protected RefundStripePaymentService $service ;
    public function __construct(RefundStripePaymentService $service){
        $this->service = $service ;
    }
    /**
     * Get all hotel bookings with their relationships and optional filtering
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllHotelBookings(array $filters = []){
        return HotelBooking::with(['user' ,'room' ,'payments'])->filter($filters)->paginate(10);
    }
    /**
     * Create a new booking, handling whether the booker is a customer or hotel agent
     * @param array $data
     * @return HotelBooking
     */
    public function createBooking(array $data){
        $user = JWTAuth::parseToken()->authenticate();
        $userBookingHotel = $user->hasRole('customer') ? $user->id : $data['user_id'];
        $hotelBooking = HotelBooking::create([
            'user_id'         => $userBookingHotel,
            'room_id'         => $data['room_id'],
            'check_in_date'   => $data['check_in_date'],
            'check_out_date'  => $data['check_out_date'],
            'booking_date'    => Carbon::now() , // When the record was created in the database
            'status'          => 'pending'
        ]);
        dispatch(new HotelBookingPendingEmailJob($hotelBooking->id));
        return $hotelBooking ;
    }
    /**
     * Update a booking (users can only modify check-in and check-out times)
     * @param array $data
     * @param \App\Models\HotelBooking $hotelBooking
     * @return HotelBooking
     */
    public function updateBooking(array $data , HotelBooking $hotelBooking){
        $hotelBooking->update($data);
        return $hotelBooking->refresh();
    }

    public function cancelBooking(HotelBooking $hotelBooking){
        $message = "No refund required.";
        $AuthUser = JWTAuth::parseToken()->authenticate();

        $isOwner = $AuthUser->hasRole('customer') && $hotelBooking->user_id === $AuthUser->id ;
        $isAgent = $AuthUser->hasRole('hotel_agent');

        // Only booking owner (customer) or hotel agent can cancel - all others denied
        if(!$isOwner && !$isAgent){
            return $this->getMessage('error','you cannot execute this action.' ,403);
        }

        if(in_array($hotelBooking->status,['failed','complete','cancelled'])){
            return $this->getMessage('error' ,'Booking cannot be cancelled in its current state.', 400);
        }
        if($hotelBooking->status === 'confirmed'){

            // fetch the payment associated with the booking.
            $refundResult = $this->service->refunde($hotelBooking);

            // Failed refund money
            if($refundResult['status'] === 'failed'){
                return $this->getMessage('error', 'Refund failed: '.$refundResult['message'], 400);
            }

            $message = 'Refund processed successfully.';
        }

        $hotelBooking->update([
            'status' => 'cancelled'
        ]);
        return $this->getMessage('success','Booking Cancelled successfully '.$message ,200);
        #TODO القيام بتغيير حالة الحجز عندما يقترب موعد الحجز ولم يتم الدفع
        #TODO إرسال تذكيرات إلى المستخدم لكي يقوم بالدفع
    }
    private function getMessage($status , $message , $code){
        return [
            'status'  =>$status ,
            'message' => $message ,
            'code'    => $code
        ];
    }
}

