<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hotel::factory()->count(20)->create()->each(
            function($hotel){
                $hotel->images()->create([
                    'image_path'=> fake()->imageUrl(640 , 480, 'hotel',true)
                ]);
        });
    }
}
