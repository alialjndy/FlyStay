<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ManageUserApiTest extends TestCase
{
    private function getToken(){
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        return $token ;
    }
    /**
     * A basic feature test example.
     */
    public function test_get_all_users(){
        $token = $this->getToken();
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/user');
        $response->assertStatus(200);
    }
    public function test_get_user_info(){
        $token = $this->getToken();
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/user/2');
        $response->assertStatus(200);
    }
}
