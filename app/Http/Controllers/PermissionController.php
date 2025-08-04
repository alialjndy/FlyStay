<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\CreatePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Services\Permission\PermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $permissionService ;
    public function __construct(PermissionService $permissionService){
        $this->permissionService = $permissionService ;
    }
    /**
     * Retrieve a paginated list of all permissions with their associated roles.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        #TODO تخصيص كل أفعال هذا الملف إلى الأدمن فقط Admin
        $permissions = $this->permissionService->getAllPermissions();
        return self::paginated($permissions,PermissionResource::class);
    }

    /**
     * Create a new permission using validated request data.
     * @param \App\Http\Requests\Permission\CreatePermissionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreatePermissionRequest $request)
    {
        $permission = $this->permissionService->store($request->validated());
        return self::success([$permission]);
    }
    /**
     * Display the details of a specific permission, including its associated roles.
     * @param \Spatie\Permission\Models\Permission $permission
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Permission $permission)
    {
        $permission = $permission->load('roles');
        return self::success([$permission],200,'Retrieved successfully');
    }

    /**
     * Update an existing permission using validated request data.
     * @param \App\Http\Requests\Permission\UpdatePermissionRequest $request
     * @param \Spatie\Permission\Models\Permission $permission
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission = $this->permissionService->update($request->validated(),$permission);
        return self::success([$permission]);
    }

    /**
     * Delete the specified permission.
     * @param \Spatie\Permission\Models\Permission $permission
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return self::success();
    }
}
