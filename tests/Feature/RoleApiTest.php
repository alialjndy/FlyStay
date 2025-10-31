<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleApiTest extends TestCase
{
    use WithFaker ;
    private function getToken(){
        $user = $this->getUser('admin');
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
        $payload = ['name' => fake()->name()];

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
        $payload = ['name' => $this->faker->name];

        $role = Role::whereNotIn('name', ['admin' , 'customer' , 'flight_agent' , 'hotel_agent' , 'finance_officer'])->first();

        $resposne = $this->withHeaders(['Authorization' => "Bearer $token"])->putJson("/api/role/$role->id" ,$payload);
        $resposne->assertStatus(200)->assertJsonStructure(['data' => [['id', 'name','guard_name']]]);
    }

    public function test_delete_role(){
        $token = $this->getToken();

        // we will delete role not used in authentication
        $role = Role::whereNotIn('name', ['admin' , 'customer' , 'flight_agent' , 'hotel_agent' , 'finance_officer'])->first();

        $resposne = $this->withHeaders(['Authorization' => "Bearer $token"])->deleteJson("/api/role/$role->id");
        $resposne->assertStatus(200);
    }
    public function test_failed_validation_create_role(){
        $token = $this->getToken();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/role' , []);
        $response->assertUnprocessable();

    }
}
