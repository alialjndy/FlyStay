<?php

namespace Database\Seeders;

use App\Models\FlightCabin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlightCabinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FlightCabin::factory()->count(20)->create();
    }
}
