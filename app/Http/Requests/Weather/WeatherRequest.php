<?php

namespace App\Http\Requests\Weather;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class WeatherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user? true : false ;
        }catch(Exception){
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
            'city'       => 'required|string|max:50' ,
            'targetDate' => 'required|date'
        ];
    }
public function failedValidation(Validator $validator){
        throw new HttpResponseException(
            response()->json([
                'status'  => 'failed',
                'message' => 'Validation failed. Please check your input.',
                'errors'  => $validator->errors(),
            ],422)
        );
    }
    public function attributes(){
        return [
            'city'       => 'City Name' ,
            'targetDate' => 'Target Date'
        ];
    }
    public function messages(){
        return [
            'city.required'         => 'please enter a city name.',
            'city.string'           => 'The city name must be a string.',
            'city.max'              => 'The city anme may not be greater than 50 characters.',
            'targetDate.required'   => 'Please Enter a date.',
            'targetDate.date'       => 'Please Enter a valid date.',
        ];
    }
}
