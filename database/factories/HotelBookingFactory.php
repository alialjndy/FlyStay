<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotelBooking>
 */
class HotelBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkInDate = Carbon::now()->addDays(rand(1, 30));
        $checkOutDate = (clone $checkInDate)->addDays(rand(1, 14));

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'room_id' => Room::inRandomOrder()->first()->id,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'booking_date' => Carbon::now(),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'complete', 'cancelled', 'failed'])
        ];
    }
}
