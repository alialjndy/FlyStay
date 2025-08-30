<?php

namespace App\Http\Requests\Room;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user->hasAnyRole(['hotel_agent','admin']);
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
            'hotel_id'=>'nullable|exists:hotels,id',
            'room_type'=>'nullable|in:Single,Double,Suite',
            'price_per_night'=>['nullable', 'numeric','min:0'],
            'capacity'=>'nullable|integer|min:0',
            'description'=>'nullable|string',
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
            'hotel_id'          =>'Hotel ID',
            'room_type'         =>'Room Type',
            'price_per_night'   =>'price per night',
            'capacity'          =>'Capacity',
            'description'       =>'Description',
        ];
    }
    public function messages(){
        return [
            'string'        => 'The :attribute must be a valid string.',
            'numeric'       => 'The :attribute must be a valid number.',
            'integer'       => 'The :attribute must be a valid integer.',
            'exists'        => 'The :attribute is invalid.',
            'in'            => 'The selected :attribute is invalid. Allowed values: Single, Double, Suite.',
            'min.string'    => 'The :attribute must be at least :min characters.',
            'min.numeric'   => 'The :attribute must be at least :min.',
        ];
    }
}
