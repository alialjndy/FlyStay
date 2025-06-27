<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departure = $this->faker->dateTimeBetween('+1 days', '+1 month');
        $arrival = (clone $departure)->modify('+2 hours');
        return [
            'airline' => $this->faker->company(),
            'flight_number' => strtoupper($this->faker->bothify('??###')),
            'departure_airport_id' => rand(1, 20),
            'arrival_airport_id' => rand(1, 20),
            'departure_time' => $departure->format('Y-m-d H:i:s'),
            'arrival_time' => $arrival->format('Y-m-d H:i:s'),
        ];
    }
}
