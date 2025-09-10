<?php

namespace App\Http\Requests\Flight;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilterFlightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = (bool) JWTAuth::parseToken()->authenticate();
            return $user ?? false ;
        }catch(Throwable){
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
            'old_flights'=>'nullable|boolean',
            'later_flight'=>'nullable|boolean',
            'airline'=>'nullable|string',
            'from_date'=>'nullable|date',
            'to_date'=>'nullable|date' ,
            'arrival_country'=>'nullable|string' ,
            'departure_country'=>'nullable|string' ,
            'arrival_city'=>'nullable|string' ,
            'departure_city'=>'nullable|string'
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
    public function attributes(){
        return [
            'old_flights'=>'Old Flights',
            'later_flight'=>'Later Flights',
            'airline'=>'Airline',
            'from_date'=>'From Date',
            'to_date'=>'To Date'
        ];
    }
    public function messages()
    {
        return [
            'boolean'=>'The :attribute must be a boolean value.',
            'string'=>'The :attribute must be a valid string.',
            'date'=>'The :attribute must be a valid date and time.'
        ];
    }
}
