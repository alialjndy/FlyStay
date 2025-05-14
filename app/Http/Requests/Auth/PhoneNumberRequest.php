<?php

namespace App\Http\Requests\Auth;

use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PhoneNumberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        #TODO التحقق من أن المستخدم يحق له تغيير رقم الهاتف أو ملئ رقم الهاتف
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
            'phone_number'=>['required','string','min:8','max:15','unique:users,phone_number', new ValidPhoneNumber],
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
            'phone_number'=>'Phone Number'
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
