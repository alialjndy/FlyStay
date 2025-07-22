<?php

namespace App\Http\Requests\FlightBooking;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateFlightBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user && $user->hasAnyRole(['customer', 'flight_agent']);
    }
    protected function failedAuthorization(){
        throw new AuthorizationException('you cannot perform this action.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'flight_cabins_id'=>'required|exists:flight_cabins,id',
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
            'flight_cabins_id' => 'Flight Details',
        ];
    }
    public function messages(){
        return [
            'required' => 'The :attribute field is required.',
            'flight_cabins_id.required' => 'Please select a flight cabin.',
            'flight_cabins_id.exists' => 'The selected flight cabin does not exist.'
        ];
    }
}
