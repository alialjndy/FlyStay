<?php

namespace App\Http\Requests\Auth;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user? true : false ;
        }catch(Exception){
            return false ;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password'=>'required|string',
            'new_password'=>['required','confirmed',Password::min(8)->letters()->numbers()->mixedCase()->symbols()]
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        throw new HttpResponseException(
            response()->json([
                'status' =>'failed',
                'message'=>'failed validation please confirm the input',
                'errors'=>$validator->errors()
             ],422)
        );
    }
    public function attributes(){
        return [
            'current_password' =>'Current Password',
            'new_password'     =>'New Passowrd',
        ];
    }
    public function messages(){
        return [
            'required'  => 'The :attribute field is required.',
            'string'    => 'The :attribute must be a valid string.',
            'confirmed' => 'The :attribute confirmation does not match.',
        ];
    }
}
