<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class FlightBookingApiTest extends TestCase
{
    public function test_create_flight_booking_successfully(){
        $user = $this->getUser('customer');
        $token = JWTAuth::fromUser($user);

        $payload = [
            'flight_cabins_id' => 5
        ];

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->postJson('/api/flight-bookings' , $payload);

        $response->assertCreated();
    }

    public function test_update_flight_booking_successfully(){
        $user = $this->getUser('customer');
        $token = JWTAuth::fromUser($user);

        $payload = [
            'flight_cabins_id' => 6
        ];

        // Retrieve the latest flight booking for this user
        // Only bookings in pending status should be considered (not completed)
        $flightBooking = $user->flightBookings()->latest()->first();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->putJson("/api/flight-bookings/{$flightBooking->id}" , $payload);

        $response->assertOk();
    }

    public function test_cancel_flight_booking_successfully(){
        $user = $this->getUser('customer');
        $token = JWTAuth::fromUser($user);

        $flightBooking = $user->flightBookings()->latest()->first();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->postJson("/api/flight-bookings/{$flightBooking->id}/cancel");

        $response->assertOk();
    }

    public function test_unauthorized_delete_flight_booking(){
        $user = $this->getUser('customer');
        $token = JWTAuth::fromUser($user);

        //
        $flightBooking = $user->flightBookings()->latest()->first();
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->deleteJson("/api/flight-bookings/{$flightBooking->id}");

        $response->assertForbidden();
    }
}
