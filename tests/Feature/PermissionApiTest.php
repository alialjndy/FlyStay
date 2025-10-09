<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PermissionApiTest extends TestCase
{
    use WithFaker ;
    private function getUser(){
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        return $token ;
    }
    /**
     * A basic feature test example.
     */
    public function test_get_all_permissions(){
        $token = $this->getUser();


        $resposne = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/permission');
        $resposne->assertStatus(200);
    }

    public function test_create_permissions(){
        $token = $this->getUser();
        $payload = ['name' => $this->faker->name];

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/permission' , $payload);
        $response->assertStatus(201);
    }

    public function test_show_one_permission(){
        $token = $this->getUser();
        $resposne = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson("/api/permission/1");

        $resposne->assertStatus(200)->assertJsonStructure(['data' => [['id', 'name','guard_name']]]);
    }

    public function test_update_permission(){
        $token = $this->getUser();
        $payload = ['name' => 'udpate Name'];

        $resposne = $this->withHeaders(['Authorization' => "Bearer $token"])->putJson("/api/permission/1" ,$payload);
        $resposne->assertStatus(200)->assertJsonStructure(['data' => [['id', 'name','guard_name']]]);
    }

    public function test_delete_permission(){
        $token = $this->getUser();
        $resposne = $this->withHeaders(['Authorization' => "Bearer $token"])->deleteJson("/api/permission/1");
        $resposne->assertStatus(200);
    }
}
