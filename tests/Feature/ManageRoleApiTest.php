<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ManageRoleApiTest extends TestCase
{
    private function getToken(){
        $user = $this->getUser('admin');
        $token = JWTAuth::fromUser($user);
        return $token ;
    }
    /**
     * Test assigning a role to a user.
     * @return void
     */
    public function test_assign_role_to_user(){
        $token = $this->getToken();

        $role = Role::inRandomOrder()->first();

        // User not have the role.
        $user = User::whereDoesntHave('roles' , function($query) use ($role){
            $query->where('name' , '=' , $role->name);
        })->first();

        $payload = [
            'user_id' =>$user->id ,
            'name' => $role->name
        ];

        $respnonse = $this->withHeaders(['Authorization' => "Bearer $token"])
                          ->postJson('/api/assign-role' , $payload);

        $respnonse->assertStatus(200);
    }
    public function test_remove_role_from_user(){
        $token = $this->getToken();

        // Select a random user that have a roles.
        $user = $this->getUser('finance_officer');

        // Select a role that the user already has
        $role = $user->roles()->inRandomOrder()->first();
        $payload = [
            'user_id' =>$user->id ,
            'name' => $role->name
        ];

        $respnonse = $this->withHeaders(['Authorization' => "Bearer $token"])
                          ->postJson('/api/remove-role' , $payload);

        $respnonse->assertStatus(200);
    }
}
