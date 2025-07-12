<?php

namespace App\Http\Requests\Role;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RemoveRoleFromUserRequest extends FormRequest
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
            'name'=>'required|exists:roles,name',
            'user_id'=>'required|exists:users,id'
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
    public function attributes(){
        return [
            'name' =>'Role Name',
            'user_id'=>'User ID',
        ];
    }
    public function messages(){
        return [
            'required'  => 'The :attribute field is required.',
            'exists'    =>'The :attribute vield value is invalid.'
        ];
    }
}
