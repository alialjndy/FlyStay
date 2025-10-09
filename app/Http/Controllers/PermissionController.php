<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\CreatePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Services\Permission\PermissionService;
use App\Traits\ManageCache;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use ManageCache ;
    protected $permissionService ;
    public function __construct(PermissionService $permissionService){
        $this->permissionService = $permissionService ;
    }
    /**
     * Retrieve a paginated list of all permissions with their associated roles.
     * The result is cached for 10 days.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cacheKey = 'all_permissions' ;
        $permissions = $this->getFromCache('permissions' , $cacheKey)
            ?? $this->addToCache(
                'permissions' ,
                $cacheKey ,
                $this->permissionService->getAllPermissions() ,
                now()->addDays(10)
            );
        return self::paginated($permissions,PermissionResource::class);
    }

    /**
     * Create a new permission using validated request data.
     * Clears the cached permissions after creation.
     * @param \App\Http\Requests\Permission\CreatePermissionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreatePermissionRequest $request)
    {
        $permission = $this->permissionService->store($request->validated());


        $this->clearCache('permissions'); // Clear permission cache to ensure consistency.

        return self::success([$permission] , 201);
    }
    /**
     * Retrieve details of a specific permission, including its associated roles.
     * The result is cached for 10 days.
     * @param \Spatie\Permission\Models\Permission $permission
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Permission $permission)
    {
        $cacheKey = 'permission_' . $permission->id ;
        $permission = $this->getFromCache('permissions' , $cacheKey)
            ?? $this->addToCache(
                'permissions' ,
                $cacheKey ,
                $permission->load('roles'),
                now()->addDays(10)
            );
        return self::success([$permission],200,'Retrieved successfully');
    }

    /**
     * Update an existing permission using validated request data.
     * Clears the cached permissions after update.
     * @param \App\Http\Requests\Permission\UpdatePermissionRequest $request
     * @param \Spatie\Permission\Models\Permission $permission
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission = $this->permissionService->update($request->validated(),$permission);

        $this->clearCache('permissions') ;  // Clear permission cache to reflect updates
        return self::success([$permission]);
    }

    /**
     * Delete the specified permission and clear cached permission data.
     * @param \Spatie\Permission\Models\Permission $permission
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        $this->clearCache('permissions'); // Clear permission cache after deletion
        return self::success();
    }
}
