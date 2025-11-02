<?php

namespace Tests\Feature;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class HotelBookingApiTest extends TestCase
{
    public function test_sucess_create_hotel_booking(){
        $user = $this->getUser('customer');
        $token = JWTAuth::fromUser($user);

        // Get random dates
        $checkInDate = Carbon::now()->addDays(rand(1, 30));
        $checkOutDate = (clone $checkInDate)->addDays(rand(1, 14));

        $payload = [
            'room_id' => Room::inRandomOrder()->value('id'),
            'check_in_date' => $checkInDate ,
            'check_out_date'=> $checkOutDate
        ];

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->postJson('/api/hotel-bookings' , $payload);

        $response->assertCreated();
    }
    public function test_sucess_update_hotel_booking(){
        $user = $this->getUser('customer');
        $token = JWTAuth::fromUser($user);


        $checkInDate = Carbon::now()->addDays(rand(1, 30));
        $checkOutDate = (clone $checkInDate)->addDays(rand(1, 14));

        $payload = [
            'room_id' => Room::inRandomOrder()->value('id'),
            'check_in_date' => $checkInDate ,
            'check_out_date'=> $checkOutDate
        ];

        $latestBooking = $user->hotelBookings()->latest()->first();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->putJson("/api/hotel-bookings/{$latestBooking->id}" , $payload);

        $response->assertOk();
    }
    public function test_failed_delete_hotel_booking(){
        $user = $this->getUser('customer');
        $token = JWTAuth::fromUser($user);

        $latestBooking = $user->hotelBookings()->latest()->first();
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->deleteJson("/api/hotel-bookings/{$latestBooking->id}");

        // Customer can't delete any booking
        $response->assertForbidden();
    }
}
