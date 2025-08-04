<?php

namespace App\Http\Requests\Hotel;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateHotelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user && $user->hasAnyRole(['admin','hotel_agent']);
        }catch(Exception $e){
            return false ;
        }
    }
    protected function failedAuthorization(){
        throw new AuthenticationException('You are not authorized to perform this action.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'city_id'       => 'required|exists:cities,id',
            'rating'        => 'nullable|integer|min:1|max:5',
            'description'   => 'nullable|string',
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
            'city_id'       =>'City ID',
            'rating'        =>'Rating',
            'description'   =>'Description',
        ];
    }
    public function messages(){
        return [
            'required'  => 'The :attribute field is required.',
            'string'    => 'The :attribute must be a valid string.',
            'min'       => 'The :attribute must be at least :min characters.',
            'max'       => 'The :attribute may not be greater than :max characters.',
            'exists'    => 'The :attribute is invalid',
        ];
    }
}
