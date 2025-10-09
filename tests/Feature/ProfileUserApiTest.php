<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileUserApiTest extends TestCase
{
    private function getUser(){
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user ;
    }
    public function test_get_profile_info(){
        $user = $this->getUser();

        $response = $this->actingAs($user)->getJson("api/me");
        $response->assertStatus(200);
    }
}
