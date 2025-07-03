<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultImages = ['defaultRoom1.jpg','defaultRoom2.jpg','defaultRoom3.jpg','defaultRoom4.jpg','defaultRoom5.jpg'];
        Room::factory()->count(100)->create()->each(
            function($room)use($defaultImages){
                $randomImage = $defaultImages[array_rand($defaultImages)];
                $room->images()->create([
                    'image_path'=>'Images/' .$randomImage
                ]);
            }
        );
    }
}
