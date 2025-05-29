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
            $user = JWTAuth::parseToken()->authenticate();
            return $user ? true : false ;
        }catch(Exception $e){
            return false ;
        }
    }
    protected function failedAuthorization(){
        throw new AuthorizationException('you cannot execute this action.');
    }
    protected function prepareForValidation(){
        $cleanName = preg_replace('/[^pL\s\-]/u','',$this->name ?? '');
        $this->merge([
            'name'=>$cleanName
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
            'name'=>'nullable|string'
        ];
    }
}
