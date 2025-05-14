<?php

namespace App\Http\Requests\Auth;

use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password ;

class RegisterRequest extends FormRequest
{
    /**
     * Allow all users to use this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Prepare the data for validation by modifying the input before it is validated.
     * This method is automatically called before the validation rules are applied.
     * @return void
     */
    public function prepareForValidation(){
        $this->merge([
            'email'=> strtolower($this->email),
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
            'name'        =>'required|string|min:3|max:100|unique:users,name',
            'email'       =>'required|email|unique:users,email',
            'password'    =>['required','confirmed',Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
            'phone_number'=>['required','string','min:8','max:15','unique:users,phone_number', new ValidPhoneNumber],
        ];
    }
    /**
     * Handle the failed validation and return a custom JSON response with validation errors.
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
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
            'password'    =>'Password',
            'phone_number'=>'Phone Number'
        ];
    }
    public function messages(){
        return [
            'required'  => 'The :attribute field is required.',
            'string'    => 'The :attribute must be a valid string.',
            'email'     => 'Please enter a valid email address.',
            'min'       => 'The :attribute must be at least :min characters.',
            'max'       => 'The :attribute may not be greater than :max characters.',
            'unique'    => 'The :attribute has already been taken.',
            'confirmed' => 'The :attribute confirmation does not match.',
        ];
    }
}
