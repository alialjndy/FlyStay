<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ManageCityApiTest extends TestCase
{
    private function getCustomerUser(){
        return $this->getUser('customer');
    }
    public function test_get_all_cities(){
        $user = $this->getCustomerUser();

        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/get-all-cities');

        $response->assertOk();
    }
    /**
     * test Unauthenticated user
     */
    public function test_un_authenticated_get_all_cities(){
        $response = $this->getJson('/api/get-all-cities');

        $response->assertUnauthorized();
    }

    public function test_filter_get_cities(){
        $user = $this->getCustomerUser();

        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/get-all-cities?name=Goroka');

        $response->assertOk();
        $response->assertJsonFragment(['name' => 'Goroka']);
    }

}
