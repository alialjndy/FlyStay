<?php

namespace App\Jobs;

use App\Mail\HotelBookingCancelledEmail;
use App\Models\HotelBooking;
use App\Models\User;
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
    protected $userId ;
    public function __construct($userId , $hotelBookingId)
    {
        $this->hotelBookingId = $hotelBookingId ;
        $this->userId = $userId ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::findOrFail($this->userId);
        Mail::to($user->email)->send(new HotelBookingCancelledEmail($this->hotelBookingId));
    }
}
