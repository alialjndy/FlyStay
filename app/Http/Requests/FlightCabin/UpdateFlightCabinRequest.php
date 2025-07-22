<?php

namespace App\Http\Requests\FlightCabin;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateFlightCabinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user && ($user->hasRole('admin') || $user->hasRole('flight_agent'));
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
            'flight_id'=>'nullable|exists:flights,id',
            'class_name'=>'nullable|in:Economy,Business,First',
            'price'=>'nullable|numeric',
            'available_seats'=>'nullable|integer|min:0',
            'note'=>'nullable|string|min:0'
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
            'flight_id' => 'Flight',
            'class_name' => 'Class Name',
            'price' => 'Price',
            'available_seats' => 'Available Seats',
            'note'=>'Note'
        ];
    }
    public function messages(){
        return [
            'exists' => 'The selected :attribute is invalid.',
            'in' => 'The selected :attribute must be one of: Economy, Business, First.',
            'numeric' => 'The :attribute must be a number.',
            'min' => 'The :attribute must be at least :min.',
            'string'=>'The :attribute field value must be a string.'
        ];
    }
}
