<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\AssignPermissionRequest;
use App\Http\Requests\Permission\RemovePermissionRequest;
use App\Services\Permission\PermissionAdminService;
use Illuminate\Http\Request;

class AdminPermissionController extends Controller
{
    private $service ;
    public function __construct(PermissionAdminService $service){
        $this->service = $service ;
    }
    /**
     * Summary of assignPermissionToUser
     * @param \App\Http\Requests\Permission\AssignPermissionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignPermissionToUser(AssignPermissionRequest $request){
        $info = $this->service->assignPermissionToUser($request->validated());
        return $info['status'] == 'success' ?
        self::success([$info['data']],$info['code'],$info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]);
    }
    /**
     * Summary of assignPermissionToRole
     * @param \App\Http\Requests\Permission\AssignPermissionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignPermissionToRole(AssignPermissionRequest $request){
        $info = $this->service->assignPermissionToRole($request->validated());
        return $info['status'] == 'success' ?
        self::success([$info['data']],$info['code'],$info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]);
    }
    /**
     * Summary of removePermissionFromUser
     * @param \App\Http\Requests\Permission\RemovePermissionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function removePermissionFromUser(RemovePermissionRequest $request){
        $info = $this->service->removePermissionFromUser($request->validated());
        return $info['status'] == 'success' ?
        self::success([$info['data'] ?? null],$info['code'],$info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]);
    }
    /**
     * Summary of removePermissionFromRole
     * @param \App\Http\Requests\Permission\RemovePermissionRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function removePermissionFromRole(RemovePermissionRequest $request){
        $info = $this->service->removePermissionFromRole($request->validated());
        return $info['status'] == 'success' ?
        self::success([$info['data'] ?? null],$info['code'],$info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]);
    }
}
