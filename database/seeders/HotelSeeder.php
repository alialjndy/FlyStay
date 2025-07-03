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
        $defaultImages = ['default1.jpg', 'default2.jpg', 'default3.jpg','default4.jpg','default5.jpg'];
        Hotel::factory()->count(20)->create()->each(
            function($hotel) use ($defaultImages){
                $randomImage = $defaultImages[array_rand($defaultImages)];
                $hotel->images()->create([
                    'image_path'=> 'Images/' . $randomImage
                ]);
        });
    }
}
