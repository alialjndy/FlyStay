<?php

namespace App\Http\Requests\Flight;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateFlightRequest extends FormRequest
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
            'airline'=>'required|string',
            'flight_number'=>'required|string',
            'departure_airport_id'=>'required|exists:airports,id',
            'arrival_airport_id'=>'required|exists:airports,id',
            'departure_time'=>'required|date',
            'arrival_time'=>'required|date|after:departure_time'
        ];
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
    public function withValidator(Validator $validator){
        $validator->after(function($validator){
            $departure = $this->input('departure_airport_id');
            $arrival = $this->input('arrival_airport_id');

            if(!$departure || !$arrival){return ;} // if one of them is missing, skip this check

            // Check if departure and arrival airports are the same
            if($departure === $arrival){
                $validator->errors()->add('arrival_airport_id', 'Arrival airport must be different from departure airport.');
            }
        });
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
            'required'=>'The :attribute field is required.',
            'string'=>'The :attribute must be a valid string.',
            'exists'=>'The selected :attribute is invalid.',
            'date'=>'The :attribute must be a valid date and time.',
            'after'=>'The :attribute must be after the Departure Time.',
        ];
    }
}
