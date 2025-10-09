<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ManagePermissionApiTest extends TestCase
{
    private function getToken(){
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        return $token ;
    }
    /**
     * Test assigning a permission to a user
     * @return void
     */
    public function test_assign_permission_to_user(){
        // Select a random permission from the database
        $permission = Permission::inRandomOrder()->first();

        // Select a user who does NOT already have this permission
        $user = User::whereDoesntHave('permissions' , function ($query) use ($permission){
            $query->where('permissions.id' , $permission->id);
        })->inRandomOrder()->first();

        $payload = [
            'user_id' => $user->id,
            'permission_name' => $permission->name,
        ];

        $token = $this->getToken();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->postJson('/api/assign-permission-to-user' , $payload);
        $response->assertStatus(200);
    }
    /**
     * Test assigning a permission to a role
     */
    public function test_assign_permission_to_role(){

        // Select a random permission
        $permission = Permission::inRandomOrder()->first();

        // Select a role that does not already have this permission
        $role = Role::whereDoesntHave('permissions' , function ($query) use ($permission){
            $query->where('permissions.id' , $permission->id);
        })->inRandomOrder()->first();

        $payload = [
            'role_name' => $role->name,
            'permission_name' => $permission->name,
        ];

        $token = $this->getToken();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->postJson('/api/assign-permission-to-role' , $payload);
        $response->assertStatus(200);
    }
    /**
     * Test removing a permission from a user
     * @return void
     */
    public function test_remove_permission_from_user(){
        $token = $this->getToken();

         // Select a random user who has at least one permission
        $user = User::has('permissions')->inRandomOrder()->first();

        // Select a random permission that the user currently has
        $permission = $user->permissions()->inRandomOrder()->first();

        $payload = [
            'user_id' => $user->id ,
            'permission_name' => $permission->name
        ];

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/remove-permission-from-user' , $payload);
        $response->assertStatus(200);
    }
    /**
     * Test removing a permission from a role
     * @return void
     */
    public function test_remove_permission_from_role(){
        $token = $this->getToken();

        // Select a random role
        $role = Role::inRandomOrder()->first();

        // Select a random permission that the role currently has
        $permission = $role->permissions()->inRandomOrder()->first();
        $payload = [
            'permission_name' => $permission->name ,
            'role_name' => $role->name
        ];

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/remove-permission-from-role' , $payload);
        $response->assertStatus(200);
    }
}
