<?php
namespace App\Services\Role;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAdminService{
    /**
     * Summary of assignRoleToUser
     * @param mixed $data
     * @return array{message: mixed, status: string}
     */
    public function assignRoleToUser($data){
        $user = User::find($data['user_id']);
        $role = Role::where('name',$data['name'])->first();

        //
        if(!$user->hasRole($role->name)){
            $user->assignRole($role);
            return $this->success('Done Successfully!',$user->getRoleNames());
        }
        return $this->error('User already has the role');
    }
    /**
     * Summary of removeRoleFromUser
     * @param mixed $data
     * @return array{message: mixed, status: string}
     */
    public function removeRoleFromUser($data){
        $user = User::find($data['user_id']);
        $role = Role::where('name',$data['name'])->first();

        //
        if($user->hasRole($role->name)){
            $user->removeRole($role);
            return $this->success('Done Successfully!');
        }
        return $this->error('User has not have the role');
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
