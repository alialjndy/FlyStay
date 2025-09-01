<?php

namespace App\Http\Requests\Rating;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateRatingRequest extends FormRequest
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
            'hotel_id'    => 'nullable|exists:hotels,id',
            'rating'      => 'nullable|integer|min:1|max:5',
            'description' => 'nullable|string|max:255',
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
            'hotel_id'    => 'hotel',
            'rating'      => 'rating',
            'description' => 'description',
        ];
    }
    public function messages(){
        return [
            'hotel_id.exists'   => 'The selected hotel does not exist.',
            'rating.integer'    => 'The rating must be a number.',
            'rating.min'        => 'The rating must be at least 1.',
            'rating.max'        => 'The rating may not be greater than 5.',
            'description.string'=> 'The description must be a valid string.',
            'description.max'   => 'The description may not be greater than 255 characters.',
        ];
    }
}
