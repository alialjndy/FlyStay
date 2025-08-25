<?php

namespace App\Jobs;

use App\Mail\HotelBookingConfirmedEmail;
use App\Models\HotelBooking;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class HotelBookingConfirmedEmailJob implements ShouldQueue
{
    use Queueable , SerializesModels , Dispatchable , InteractsWithQueue;
    public int $tries = 3 ;
    public int $timeout = 30 ; // seconds
    public int $backoff = 10 ; // seconds between retries

    /**
     * Create a new job instance.
     */
    public $paymentId ;
    public $bookingId ;
    public function __construct($paymentId , $bookingId)
    {
        $this->paymentId = $paymentId ;
        $this->bookingId = $bookingId ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payment = Payment::findOrFail($this->paymentId);
        $booking = HotelBooking::findOrFail($this->bookingId);
        $user = $booking->user ;
        $room = $booking->room ;

        $data = [
            'user_name'=>$user->name ,
            'room_details'=>[
                'Hotel Name'=>$room->hotel->name,
                'room_type'=>$room->room_type ,
                'price_per_night'=>$room->price_per_night ,
                'capacity'=>$room->capacity ,
            ],
            'check_in_date'=>$booking->check_in_date ,
            'check_out_date'=>$booking->check_out_date ,

            //Payment Info

            'Amount'=>$payment->amount  ,
            'Date'=>$payment->date ,
            'Payment_method'=>$payment->method   ,
            'Payment Status'=>$payment->status ,
        ];
        Mail::to($user->email)->send(new HotelBookingConfirmedEmail($data));

    }
}
