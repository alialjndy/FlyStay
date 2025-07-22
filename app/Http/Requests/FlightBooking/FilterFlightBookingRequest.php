<?php

namespace App\Http\Requests\FlightBooking;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilterFlightBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user && $user->hasAnyRole(['admin', 'flight_agent']);
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
            'status' => 'nullable|in:pending,confirmed,cancelled',
            'from_date' => 'nullable|date|required_with:to_date',
            'to_date' => 'nullable|date|after_or_equal:from_date|required_with:from_date',
            'flight_cabin' => 'nullable|in:Economy,Business,First'
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
            'status' => 'Status Booking',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'flight_cabin' => 'Flight Cabin',
        ];
    }
    public function messages(){
        return [
            'status.in' => 'The selected booking status is invalid. Please choose from: pending, confirmed, cancelled.',
            'flight_cabin.in' => 'The selected booking flight cabin is invalid. Please choose from: Economy, Business, First.',
            'from_date.date' => 'The start date must be a valid date.',
            'from_date.required_with' => 'The start date field is required when end date is present.',
            'to_date.date' => 'The end date must be a valid date.',
            'to_date.after_or_equal' => 'The end date must be equal to or after the start date.',
            'to_date.required_with' => 'The end date field is required when start date is present.',
            'flight_cabin.string' => 'The cabin class must be a string value.',
        ];
    }
}
