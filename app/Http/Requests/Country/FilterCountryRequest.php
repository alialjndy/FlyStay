<?php

namespace App\Http\Requests\Country;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class FilterCountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user ? true : false ;
        }catch(Exception $e){
            return false ;
        }
    }
    protected function failedAuthorization(){
        throw new AuthenticationException('you cannot execute this action.');
    }
    protected function prepareForValidation(){
        $cleanName = preg_replace('/[^\pL\s\-]/u','',$this->name ?? '');
        $cleanIso2 = preg_replace('/[^a-z ]/i','',$this->iso2 ?? '');
        $this->merge([
            'name'=>$cleanName,
            'iso2'=>$cleanIso2
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'nullable|string',
            'iso2'=>'nullable|string'
        ];
    }
}
