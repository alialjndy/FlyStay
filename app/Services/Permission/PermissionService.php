<?php
namespace App\Services\Permission;

use Spatie\Permission\Models\Permission;

class PermissionService{
    /**
     * Retrieve all permissions along with their associated roles.
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPermissions(){
        return Permission::with('roles')->paginate(10);
    }
    /**
     * Create a new permission using the provided data.
     * @param array $data
     * @return Permission|\Spatie\Permission\Contracts\Permission
     */
    public function store(array $data){
        return Permission::create($data);
    }
    /**
     * Update an existing permission with the given data.
     * @param array $data
     * @param \Spatie\Permission\Models\Permission $permission
     * @return Permission
     */
    public function update(array $data , Permission $permission){
        $permission->update($data);
        return $permission;
    }
}
