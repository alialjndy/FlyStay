<?php

namespace App\Jobs;

use App\Mail\HotelBookingCancelledEmail;
use App\Models\HotelBooking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendHotelBookingCancelledEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3 ;
    public int $timeout = 30 ; // seconds
    public int $backoff = 10 ; // seconds between retries
    /**
     * Create a new job instance.
     */
    protected $hotelBookingId ;
    public function __construct($hotelBookingId)
    {
        $this->hotelBookingId = $hotelBookingId ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $hotelBooking = HotelBooking::with(['user', 'room'])->find($this->hotelBookingId);
        $user = $hotelBooking->user;

        $data = [
            'user_name'     =>$user->name ,
            'room_details'  =>[
                'Hotel Name'        =>$hotelBooking->room->hotel->name,
                'room_type'         =>$hotelBooking->room->room_type ,
                'price_per_night'   =>$hotelBooking->room->price_per_night ,
                'capacity'          =>$hotelBooking->room->capacity ,
            ],
            'check_in_date' =>$hotelBooking->check_in_date ,
            'check_out_date'=>$hotelBooking->check_out_date ,
            'status'        =>$hotelBooking->status ,

        ] ;
        Mail::to($user->email)->send(new HotelBookingCancelledEmail($data));
    }
}
