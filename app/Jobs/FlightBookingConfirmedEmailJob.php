<?php

namespace App\Jobs;

use App\Mail\FlightBookingConfirmedEmail;
use App\Models\FlightBooking;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class FlightBookingConfirmedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3 ;
    public int $timeout = 30 ; // seconds
    public int $backoff = 10 ; // seconds between retries

    /**
     * Create a new job instance.
     */
    protected  $paymentId ;
    protected  $bookingId ;
    protected $userId ;
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

        // Send the confirmation email to the user
        Mail::to($user->email)->send(
            new FlightBookingConfirmedEmail($this->bookingId , $this->paymentId)
        ) ;
    }
}
