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

use function PHPUnit\Framework\isEmpty;

class ManagePermissionApiTest extends TestCase
{
    private function getToken(){
        $user = $this->getUser('admin');
        $token = JWTAuth::fromUser($user);
        return $token ;
    }
    /**
     * Test assigning a permission to a role
     */
    public function test_assign_permission_to_role(){

        // Select a random permission
        $permission = Permission::inRandomOrder()->first();

        // Select a role that does not already have this permission
        $role = Role::whereDoesntHave('permissions' , function ($query) use ($permission){
            $query->where('name' , '=' ,$permission->name);
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
