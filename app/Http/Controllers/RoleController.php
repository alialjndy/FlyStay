<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\ROle\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\Role\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $roleService ;
    public function __construct(RoleService $roleService){
        $this->roleService = $roleService ;
    }
    /**
     * Retrieve a paginated list of all roles with their associated permissions.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $roles = $this->roleService->getAllRoles();
        return self::paginated($roles , RoleResource::class);
    }

    /**
     * Create a new role using validated request data.
     * @param \App\Http\Requests\Role\CreateRoleRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreateRoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());
        return self::success([$role]);
    }

    /**
     * Display the details of a specific role, including its associated permissions.
     * @param \Spatie\Permission\Models\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        $role = $role->load('permissions');
        return self::success([$role],200,'Retrieved successfully','success');
    }

    /**
     * Update an existing role using validated request data.
     * @param \App\Http\Requests\ROle\UpdateRoleRequest $request
     * @param \Spatie\Permission\Models\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role = $this->roleService->update($request->validated(),$role);
        return self::success([$role]);
    }

    /**
     * Delete the specified role.
     * @param \Spatie\Permission\Models\Role $role
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return self::success();
    }
}
