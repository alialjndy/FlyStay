<?php

namespace App\Http\Requests\Flight;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateFlightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user && ($user->hasRole('flight_agent') || $user->hasRole('admin'));
        }catch(\Throwable $e){
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
            'airline'=>'nullable|string',
            'flight_number'=>'nullable|string',
            'departure_airport_id'=>'nullable|exists:airports,id',
            'arrival_airport_id'=>'nullable|exists:airports,id',
            'departure_time'=>'nullable|date',
            'arrival_time'=>'nullable|date|after:departure_time'
        ];
    }
    public function withValidator(Validator $validator){
        $validator->after(function($validator){
            $flight = $this->route('flight');
            if($flight && Carbon::now()->gt(Carbon::parse($flight->departure_time))){
                $validator->errors()->add('departure_time','You cannot modify a flight that has already departed.');
            }
        });
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(
            response()->json([
                'status'=>'failed',
                'message'=>'failed validation please confirm the input.',
                'errors'=>$validator->errors()
            ],422)
        );
    }
    public function attributes(){
        return [
            'airline'=>'Airline',
            'flight_number'=>'Flight Number',
            'departure_airport_id'=>'Departure Airport',
            'arrival_airport_id'=>'Arrival Airport',
            'departure_time'=>'Departure Time',
            'arrival_time'=>'Arrival Time'
        ];
    }
    public function messages(){
        return [
            'string'=>'The :attribute must be a valid string.',
            'exists'=>'The selected :attribute is invalid.',
            'date'=>'The :attribute must be a valid date and time.',
            'after'=>'The :attribute must be after the Departure Time.',
        ];
    }
}
