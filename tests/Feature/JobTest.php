<?php

namespace Tests\Feature;

use App\Jobs\FlightBookingPendingEmailJob;
use App\Mail\FlightBookingPendingEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class JobTest extends TestCase
{
    public function test_job_executes_successfully(){
        Queue::fake();
        Mail::fake();

        // Get pending flight booking
        $user = User::whereHas(
            'flightBookings' ,
            fn($q) => $q->where('status' , 'pending'))->first();

        // Create Job object
        $job = new FlightBookingPendingEmailJob($user->id , $user->flightBookings()->first()->id);

        $job->handle();

        Mail::assertSent(FlightBookingPendingEmail::class , function ($mail) use ($user){
            return $mail->hasTo($user->email);
        });
    }
}
