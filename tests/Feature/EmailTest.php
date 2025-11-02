<?php

namespace Tests\Feature;

use App\Mail\FlightBookingPendingEmail;
use App\Models\FlightBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailTest extends TestCase
{
    public function test_send_flight_booking_pending_email_successfully(){
        Mail::fake();

        $flightBooking = FlightBooking::where('status' , 'pending')->first();

        // if flightBooking is null
        $this->assertNotNull($flightBooking , 'No pending flight booking found for the test.');

        $mailable = new FlightBookingPendingEmail($flightBooking->id);
        $mailable->assertSeeInHtml($flightBooking->id);
    }
}
