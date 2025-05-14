<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token'       => 'required|string',
            'email'       => 'required|email',
            'password'    =>['required','confirmed',Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
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
            'token'       =>'Token',
            'email'       =>'Email address',
            'password'    =>'Password',
        ];
    }
    public function messages(){
        return [
            'required'  => 'The :attribute field is required.',
            'string'    => 'The :attribute must be a valid string.',
            'email'     => 'Please enter a valid email address.',
            'min'       => 'The :attribute must be at least :min characters.',
            'confirmed' => 'The :attribute confirmation does not match.',
        ];
    }
}
