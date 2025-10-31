<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ManageHotelApiTest extends TestCase
{
    /**
     * Summary of test_get_all_hotels
     * @return void
     */
    public function test_get_all_hotels(){
        $user = $this->getUser('hotel_agent');
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/hotel');
        $response->assertOk();
    }
    /**
     * Summary of test_success_create_hotel_with_upload_photo
     * @return void
     */
    public function test_success_create_hotel_with_upload_photo(){
        Storage::fake('public');

        $photo_1 = UploadedFile::fake()->image('photo1.jpg' , 600 , 600);
        $photo_2 = UploadedFile::fake()->image('photo2.jpg' , 500 , 500);

        $user = $this->getUser('hotel_agent');

        $token = JWTAuth::fromUser($user);
        $data = [
            'name' => fake()->name(),
            'city_id' => City::inRandomOrder()->value('id'),
            'images' => [$photo_1 , $photo_2] ,
        ];

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/hotel' , $data);
        $response->assertCreated();
    }
    /**
     * Summary of test_success_update_hotel_with_upload_photo_and_delete_photo
     * @return void
     */
    public function test_success_update_hotel_with_upload_photo_and_delete_photo(){
        $user = $this->getUser('hotel_agent');
        $token = JWTAuth::fromUser($user);

        Storage::fake('public');

        $hotel = Hotel::whereHas('images')->inRandomOrder()->first();

        $delete_photo = $hotel->images->first();
        $upload_photo = UploadedFile::fake()->image('photo1.jpg', 500, 500);

        $payload = [
            'name' => fake()->name(),
            'new_photos' => [$upload_photo] ,
            'deleted_photos' => [$delete_photo->id],
        ];

        $response = $this
            ->withHeaders(['Authorization' => "Bearer $token"])
            ->putJson("api/hotels/{$hotel->id}/update-with-photo" , $payload);

        $response->assertOk();
    }
    /**
     *
     */

    public function test_failed_upload_photo(){
        Storage::fake('public');

        $photo_1 = UploadedFile::fake()->image('one/photo1..jpg' , 600 , 600);
        $user = $this->getUser('hotel_agent');

        $token = JWTAuth::fromUser($user);
        $data = [
            'name' => fake()->name(),
            'city_id' => City::inRandomOrder()->value('id'),
            'images' => [$photo_1] ,
        ];

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/hotel' , $data);
        $response->assertStatus(500);
    }
    public function test_unauthorized_action(){
        $user = $this->getUser('customer');
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->postJson('/api/hotel' , []);

        $response->assertUnauthorized();
    }
}
