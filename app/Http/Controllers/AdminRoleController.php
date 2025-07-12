<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\AssignRoleRequest;
use App\Http\Requests\Role\RemoveRoleFromUserRequest;
use App\Services\Role\RoleAdminService;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    protected $service ;
    public function __construct(RoleAdminService $service){
        $this->service = $service ;
    }
    public function assignRoleToUser(AssignRoleRequest $request){
        $info = $this->service->assignRoleToUser($request->validated());
        return $info['status'] == 'success' ?
        self::success([$info['data'] ?? null ] , $info['code'] , $info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]);
    }
    public function removeRoleFromUser(RemoveRoleFromUserRequest $request){
        $info = $this->service->removeRoleFromUser($request->validated());
        return $info['status'] == 'success' ?
        self::success([$info['data']] , $info['code'],$info['message'],$info['status']) :
        self::error('Error Occurred',$info['status'],$info['code'],[$info['error_message']]) ;
    }
}
