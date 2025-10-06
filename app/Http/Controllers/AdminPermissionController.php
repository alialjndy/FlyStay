<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\AssignPermissionRequest;
use App\Http\Requests\Permission\RemovePermissionRequest;
use App\Services\Permission\PermissionAdminService;
use App\Traits\ManageCache;

class AdminPermissionController extends Controller
{
    use ManageCache ;
    private $service ;
    public function __construct(PermissionAdminService $service){
        $this->service = $service ;
    }
    /**
     * Assign a new permission to a user and clear the related user cache.
     * @param \App\Http\Requests\Permission\AssignPermissionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignPermissionToUser(AssignPermissionRequest $request){
        $info = $this->service->assignPermissionToUser($request->validated());

        // Clear cached user data since a new permission has been assigned
        $this->clearCache('users');

        return $info['status'] == 'success' ?
        self::success([$info['data']],$info['code'],$info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]);
    }
    /**
     * Assign a permission to a role and clear cached user data.
     * @param \App\Http\Requests\Permission\AssignPermissionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignPermissionToRole(AssignPermissionRequest $request){
        $info = $this->service->assignPermissionToRole($request->validated());

        // Clear user cache since role permissions have changed
        $this->clearCache('users');

        return $info['status'] == 'success' ?
        self::success([$info['data']],$info['code'],$info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]);
    }
    /**
     * Remove a specific permission from a user and clear cached user data.
     * @param \App\Http\Requests\Permission\RemovePermissionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function removePermissionFromUser(RemovePermissionRequest $request){
        $info = $this->service->removePermissionFromUser($request->validated());

        // Clear user cache since user permissions have changed
        $this->clearCache('users');

        return $info['status'] == 'success' ?
        self::success([$info['data'] ?? null],$info['code'],$info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]);
    }
    /**
     * Remove a specific permission from a role and clear cached user data.
     * @param \App\Http\Requests\Permission\RemovePermissionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function removePermissionFromRole(RemovePermissionRequest $request){
        $info = $this->service->removePermissionFromRole($request->validated());

        // Clear user cache since role permissions have changed
        $this->clearCache('users');

        return $info['status'] == 'success' ?
        self::success([$info['data'] ?? null],$info['code'],$info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]);
    }
}
