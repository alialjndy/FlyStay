<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class StripeTest extends TestCase
{
    public function test_paid_flight_booking(){
        $user = $this->getUser('customer');
        $token = JWTAuth::fromUser($user);

        $flightBooking = $user->flightBookings()->latest()->first();
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->postJson("/api/payments/flight-booking/{$flightBooking->id}");

        $response->assertOk();
    }
    public function test_paid_hotel_booking(){
        $user = $this->getUser('customer');
        $token = JWTAuth::fromUser($user);

        $hotelBooking = $user->hotelBookings()->latest()->first();
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->postJson("/api/payments/hotel-booking/{$hotelBooking->id}");

        $response->assertOk();
    }
}
