<?php

namespace App\Http\Requests\Payment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class StripePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user && ($user->hasAnyRole(['customer','flight_agent','hotel_agent','admin']));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = JWTAuth::parseToken()->authenticate();
        return [
            'user_id'=> $user->hasRole('customer') ? 'nullable|exists:users,id' : 'required|exists:users,id'
        ];
    }
    public function failedValidation(Validator $validator){
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
            'user_id' => 'User ID'
        ];
    }
    public function messages(){
        return [
           'required' => 'The :attribute field is required.',
            'exists' => 'The selected :attribute is invalid.',
        ];
    }
}
