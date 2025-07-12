<?php
namespace App\Services\Permission;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionAdminService{
    /**
     * Summary of assignPermissionToUser
     * @param mixed $data
     * @return array{code: int, error_message: mixed, status: string|array{data: mixed, message: mixed, status: string}}
     */
    public function assignPermissionToUser($data){
        $user = User::find($data['user_id']);
        $permission = Permission::where('name',$data['permission_name'])->first();

        //
        if(!$user->hasPermissionTo($permission->name)){
            $user->givePermissionTo($permission);
            return $this->success('Done Successfully!',$user->getAllPermissions());
        }
        return $this->error('User already has the permission');
    }
    /**
     * Summary of removePermissionFromUser
     * @param mixed $data
     * @return array{code: int, error_message: mixed, status: string|array{data: mixed, message: mixed, status: string}}
     */
    public function removePermissionFromUser($data){
        $user = User::find($data['user_id']);
        $permission = Permission::where('name',$data['permission_name'])->first();

        //
        if($user->hasPermissionTo($permission->name)){
            $user->revokePermissionTo($permission);
            return $this->success('Done Successfully!');
        }
        return $this->error('User has not have the permission');
    }
    /**
     * Summary of assignPermissionToRole
     * @param mixed $data
     * @return array{code: int, error_message: mixed, status: string|array{data: mixed, message: mixed, status: string}}
     */
    public function assignPermissionToRole($data){
        $permission = Permission::where('name',$data['permission_name'])->first();
        $role = Role::where('name',$data['role_name'])->first();

        //
        if(!$role->hasPermissionTo($permission->name)){
            $role->givePermissionTo($permission);
            return $this->success('Done Successfully!');
        }
        return $this->error('Role already has the permission');
    }
    /**
     * Summary of removePermissionFromRole
     * @param mixed $data
     * @return array{code: int, error_message: mixed, status: string|array{data: mixed, message: mixed, status: string}}
     */
    public function removePermissionFromRole($data){
        $permission = Permission::where('name',$data['permission_name'])->first();
        $role = Role::where('name',$data['role_name'])->first();

        //
        if($role->hasPermissionTo($permission->name)){
            $role->revokePermissionTo($permission);
            return $this->success('Done Successfully!');
        }
        return $this->error('Role has not have the permission');
    }
    private function success($message , $data = null){
        return [
            'status'=>'success',
            'message'=>$message ,
            'code'=>200,
            'data'=>$data
        ];
    }
    private function error($message){
        return [
            'status'=>'error',
            'error_message'=>$message ,
            'code'=> 400
        ];
    }
}
