<?php

namespace App\Http\Requests\City;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilterCityRequest extends FormRequest
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
            'name'=>'nullable|string|max:255'
        ];
    }
    public function attributes(){
        return [
            'name'=>'city name'
        ];
    }
    public function messages(){
        return [
            'string'    =>'The :attribute field value must be a string',
            'max'       => 'The :attribute may not be greater than :max characters.',
        ];
    }
}
