<?php

namespace App\Http\Requests\Auth;

use App\Rules\ValidPhoneNumber;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user ? true : false ;
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
            'name'        =>'nullable|string|min:3|max:100',
            'email'       =>'nullable|email|unique:users,email',
            'phone_number'=>['nullable','string','min:8','max:15','unique:users,phone_number', new ValidPhoneNumber],
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
            'name'        =>'User Name',
            'email'       =>'Email address',
            'phone_number'=>'Phone Number'
        ];
    }
    public function messages(){
        return [
            'string'    => 'The :attribute must be a valid string.',
            'email'     => 'Please enter a valid email address.',
            'min'       => 'The :attribute must be at least :min characters.',
            'max'       => 'The :attribute may not be greater than :max characters.',
            'unique'    => 'The :attribute has already been taken.',
        ];
    }
}
