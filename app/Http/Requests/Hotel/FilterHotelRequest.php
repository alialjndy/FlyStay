<?php

namespace App\Http\Requests\Hotel;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilterHotelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            return (bool) JWTAuth::parseToken()->authenticate();
        }catch(Exception $e){
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
            'name'        => 'nullable|string|max:255',
            'city'   => 'nullable|string|max:255',
            'country'     => 'nullable|string|max:255',
            'rating'      => 'nullable|integer|min:1|max:5'
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
            'name'          =>'Hotel Name',
            'city'          =>'City Name',
            'country'       =>'Country Name',
            'rating'        =>'Rating',
        ];
    }
    public function messages(){
        return [
            'string'    => 'The :attribute must be a valid string.',
            'max'       => 'The :attribute may not be greater than :max characters.',
        ];
    }
}
