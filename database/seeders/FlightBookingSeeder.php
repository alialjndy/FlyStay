<?php

namespace Database\Seeders;

use App\Models\FlightBooking;
use App\Models\FlightCabin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlightBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FlightBooking::create([
            'user_id'=> User::inRandomOrder()->first()->id,
            'flight_cabins_id'=> FlightCabin::inRandomOrder()->first()->id,
            'booking_date'=>Carbon::now(),
            'seat_number'=>1,
            'status'=>'pending',
        ]);
        FlightBooking::create([
            'user_id'=> User::inRandomOrder()->first()->id,
            'flight_cabins_id'=> FlightCabin::inRandomOrder()->first()->id,
            'booking_date'=>Carbon::now(),
            'seat_number'=>2,
            'status'=>'pending',
        ]);
        FlightBooking::create([
            'user_id'=> User::inRandomOrder()->first()->id,
            'flight_cabins_id'=> FlightCabin::inRandomOrder()->first()->id,
            'booking_date'=>Carbon::now(),
            'seat_number'=>3,
            'status'=>'pending',
        ]);
        FlightBooking::create([
            'user_id'=> User::inRandomOrder()->first()->id,
            'flight_cabins_id'=> FlightCabin::inRandomOrder()->first()->id,
            'booking_date'=>Carbon::now(),
            'seat_number'=>4,
            'status'=>'pending',
        ]);
    }
}
