<?php

namespace Database\Factories;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlightCabin>
 */
class FlightCabinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $flight = Flight::inRandomOrder()->first() ?? Flight::factory()->create();
        $cabinClasses = [
            'Economy'=>[
                'price_range'=> [50 ,300],
                'seat_range'=>[50,200]
            ],
            'Business'=>[
                'price_range'=>[300 ,800],
                'seat_range'=>[20 ,50]
            ],
            'First'=>[
                'price_range'=>[800 ,2000],
                'seat_range'=>[5, 20]
            ]
        ];
        $class = $this->faker->randomElement(array_keys($cabinClasses));
        $classInfo = $cabinClasses[$class];
        return [
            'flight_id'=>$flight->id,
            'class_name'=>$class,
            'price'=>$this->faker->numberBetween(...$classInfo['price_range']),
            'available_seats'=>$this->faker->numberBetween(...$classInfo['seat_range'])
        ];
    }
}
