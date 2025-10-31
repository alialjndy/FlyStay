<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileUserApiTest extends TestCase
{
    public function test_get_profile_info(){
        $user = $this->getUser('admin');

        $response = $this->actingAs($user)->getJson("api/me");
        $response->assertStatus(200);
    }
}
