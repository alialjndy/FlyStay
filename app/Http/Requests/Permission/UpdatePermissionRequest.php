<?php

namespace App\Http\Requests\Permission;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdatePermissionRequest extends FormRequest
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
        throw new AuthorizationException('you cannot execute this aciton.');
    }

    public function prepareForValidation(){
        $cleanName = preg_replace('/[^a-z0-9_ -]/i', '', strtolower($this->name));
        $this->merge([
            'name' => $cleanName,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|string|min:3|max:255|unique:permissions,name'
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
            'name'        =>'Permission Name',
        ];
    }
    public function messages(){
        return [
            'required'  => 'The :attribute field is required.',
            'string'    => 'The :attribute must be a valid string.',
            'min'       => 'The :attribute must be at least :min characters.',
            'max'       => 'The :attribute may not be greater than :max characters.',
            'unique'    => 'The :attribute has already been taken.',
        ];
    }
}
