<?php
namespace App\Services\Role;

use Spatie\Permission\Models\Role;

class RoleService{
    /**
     * Retrieve all roles along with their associated permissions.
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllRoles(){
        return Role::with('permissions')->paginate(10);
    }
    /**
     * Create a new role using the provided data.
     * @param array $data
     * @return Role|\Spatie\Permission\Contracts\Role
     */
    public function create(array $data){
        return Role::create($data);
    }
    /**
     * Update an existing role with the given data.
     * @param array $data
     * @param \Spatie\Permission\Models\Role $role
     * @return Role
     */
    public function update(array $data , Role $role){
        $role->update($data);
        return $role ;
    }
}
