<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleApiTest extends TestCase
{
    use WithFaker ;
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
    public function test_get_all_roles(){
        $token = $this->getToken();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/role');
        $response->assertStatus(200);
    }

    public function test_create_roles(){
        $token = $this->getToken();
        $payload = ['name' => $this->faker->name];

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/role' , $payload);
        $response->assertStatus(201);
    }

    public function test_show_one_role(){
        $token = $this->getToken();
        $resposne = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson("/api/role/1");

        $resposne->assertStatus(200);
    }
    public function test_update_role(){
        $token = $this->getToken();
        $payload = ['name' => 'Update Name'];

        $resposne = $this->withHeaders(['Authorization' => "Bearer $token"])->putJson("/api/role/6" ,$payload);
        $resposne->assertStatus(200)->assertJsonStructure(['data' => [['id', 'name','guard_name']]]);
    }

    public function test_delete_role(){
        $token = $this->getToken();
        $resposne = $this->withHeaders(['Authorization' => "Bearer $token"])->deleteJson("/api/role/6");
        $resposne->assertStatus(200);
    }
}
