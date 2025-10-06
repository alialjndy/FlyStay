<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\AssignRoleRequest;
use App\Http\Requests\Role\RemoveRoleFromUserRequest;
use App\Services\Role\RoleAdminService;
use App\Traits\ManageCache;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    use ManageCache ;
    protected $service ;
    public function __construct(RoleAdminService $service){
        $this->service = $service ;
    }
    /**
     * Assign a new role to a user and clear related user cache data.
     * @param \App\Http\Requests\Role\AssignRoleRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignRoleToUser(AssignRoleRequest $request){
        $info = $this->service->assignRoleToUser($request->validated());

        $this->clearCache('users'); // Clear user cache since a new role has been assigned

        return $info['status'] == 'success' ?
        self::success([$info['data'] ?? null ] , $info['code'] , $info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]);
    }
    /**
     * Remove an existing role from a user and clear related user cache data.
     * @param \App\Http\Requests\Role\RemoveRoleFromUserRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function removeRoleFromUser(RemoveRoleFromUserRequest $request){
        $info = $this->service->removeRoleFromUser($request->validated());

        $this->clearCache('users'); // Clear user cache since a role has been removed.

        return $info['status'] == 'success' ?
        self::success([$info['data']] , $info['code'],$info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]) ;
    }
}
