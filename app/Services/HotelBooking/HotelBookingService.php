<?php
namespace App\Services\HotelBooking;

use App\Jobs\HotelBookingPendingEmailJob;
use App\Models\HotelBooking;
use App\Models\Room;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class HotelBookingService{
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
        $AuthUser = JWTAuth::parseToken()->authenticate();

        // Only booking owner (customer) or hotel agent can cancel - all others denied
        if(!($AuthUser->hasRole('customer') && $AuthUser->id === $hotelBooking->user_id) && !$AuthUser->hasRole('hotel_agent')){
            return $this->getMessage('error','you cannot execute this action.' ,403);
        }

        // Only pending bookings can be cancelled
        if($hotelBooking->status !== 'pending'){
            return $this->getMessage('error' ,'The Hotel Booking status is not a pending', 400);
        }

        $hotelBooking->update([
            'status' => 'cancelled'
        ]);
        return $this->getMessage('success','Booking Cancelled successfully' ,200);
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

