<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SendResetLinkRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email'
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
            'email' => 'Email address',
        ];
    }
    public function messages(){
        return [
            'required'  => 'The :attribute field is required.',
            'email'     => 'Please enter a valid email address.',
            'exists'=>'The selected :attribute does not exist in our records.'
        ];
    }
}
