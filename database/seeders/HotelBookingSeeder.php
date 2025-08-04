<?php

namespace Database\Seeders;

use App\Models\HotelBooking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotelBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HotelBooking::factory()->count(10)->create();
    }
}
