<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use function Illuminate\Support\enum_value;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_id'=>rand(1,20),
            'room_type'=>$this->faker->randomElement(['Single','Double','Suite']),
            'price_per_night'=>$this->faker->randomFloat(2,5,1000),
            'capacity'=>rand(1,3),
            'description'=>$this->faker->paragraph,
        ];
    }
}
