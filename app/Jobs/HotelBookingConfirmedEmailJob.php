<?php

namespace App\Jobs;

use App\Mail\HotelBookingConfirmedEmail;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\User;
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
    public $userId ;
    public function __construct($userId , $paymentId , $bookingId)
    {
        $this->paymentId = $paymentId ;
        $this->bookingId = $bookingId ;
        $this->userId = $userId ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::findOrFail($this->userId);
        Mail::to($user->email)->send(new HotelBookingConfirmedEmail($this->bookingId , $this->paymentId));

    }
}
