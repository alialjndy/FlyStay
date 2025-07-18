<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'phone_number'=>$this->phone_number,
            'roles'=>$this->roles->map(function($role){
                return [
                    'id'=>$role->id,
                    'name'=>$role->name,
                ];
            }),
            'permissions'=>$this->permissions->map(function($permission){
                return [
                    'id'=>$permission->id,
                    'name'=>$permission->name
                ];
            })
        ];
    }
}
