<?php

namespace App\Http\Requests\Permission;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignPermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user && $user->hasRole('admin');
    }
    protected function failedAuthorization(){
        throw new AuthorizationException('you cannot execute this action.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'permission_name'=>'required|exists:permissions,name',
            'user_id'=>'nullable|exists:users,id',
            'role_name'=>'nullable|exists:roles,name'
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        throw new HttpResponseException(
            response()->json([
                'status'=>'failed',
                'message'=>'failed validation please confirm the input.',
                'errors' => $validator->errors()
            ],422)
            );
    }
    public function withValidator($validator){
        $validator->after(function ($validator) {
            if (is_null($this->input('user_id')) && is_null($this->input('role_name'))) {
                $validator->errors()->add('user_id', 'Either user_id or role_name must be provided.');
                $validator->errors()->add('role_name', 'Either user_id or role_name must be provided.');
            }
        });
    }
    public function attributes(){
        return [
            'permission_name' =>'Permission Name',
            'user_id'=>'User ID',
            'role_name'=>'Role Name'
        ];
    }
    public function messages(){
        return [
            'required'  => 'The :attribute field is required.',
            'exists'    =>'The :attribute vield value is invalid.'
        ];
    }
}
