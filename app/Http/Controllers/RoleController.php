<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\Role\RoleService;
use App\Traits\ManageCache;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ManageCache ;
    protected $roleService ;
    public function __construct(RoleService $roleService){
        $this->roleService = $roleService ;
    }
    /**
     * Retrieve a paginated list of all roles with their associated permissions.
     * The result is cached for 15 days.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cacheKey = 'all_roles' ;
        $roles = $this->getFromCache('roles' ,$cacheKey)
            ?? $this->addToCache(
                'roles' ,
                $cacheKey ,
                $this->roleService->getAllRoles() ,
                now()->addDays(15)
            );
        return self::paginated($roles , RoleResource::class);
    }

    /**
     * Create a new role using validated request data.
     * Clears the cached roles after creation.
     * @param \App\Http\Requests\Role\CreateRoleRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreateRoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());

        $this->clearCache('roles') ; // Clear cached role data after creating a new role
        return self::success([$role],201);
    }

    /**
     * Retrieve the details of a specific role, including its associated permissions.
     * The result is cached for 15 days.
     * @param \Spatie\Permission\Models\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        $cacheKey = 'role' . $role->id ;
        $role = $this->getFromCache('roles' , $cacheKey)
            ?? $this->addToCache(
                'roles' ,
                $cacheKey ,
                $role->load('permissions') ,
                now()->addDays(15)
            );
        return self::success([$role],200,'Retrieved successfully','success');
    }

    /**
     * Update an existing role using validated request data.
     * Clears the cached roles after update.
     * @param \App\Http\Requests\ROle\UpdateRoleRequest $request
     * @param \Spatie\Permission\Models\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role = $this->roleService->update($request->validated(),$role);
        $this->clearCache('roles') ;
        return self::success([$role]);
    }

    /**
     * Delete the specified role and clear cached role data.
     * @param \Spatie\Permission\Models\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();
        $this->clearCache('roles');
        return self::success();
    }
}
