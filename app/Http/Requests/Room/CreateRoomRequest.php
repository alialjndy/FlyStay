<?php

namespace App\Http\Requests\Room;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            return (bool) JWTAuth::parseToken()->authenticate(); #TODO يجب أن يكون الأدمن أو شخص له دور محدد في إنشاء غرفة
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
            'hotel_id'=>'required|exists:hotels,id',
            'room_type'=>'required|in:Single,Double,Suite',
            'price_per_night'=>['required', 'numeric','min:0'],
            'capacity'=>'required|integer|min:0',
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
            'required'      => 'The :attribute field is required.',
            'string'        => 'The :attribute must be a valid string.',
            'integer'       => 'The :attribute must be a valid integer.',
            'regex'         => 'The :attribute format is invalid.',
            'exists'        => 'The :attribute is invalid.',
            'in'            => 'The selected :attribute is invalid. Allowed values: Single, Double, Suite.',
            'min.string'    => 'The :attribute must be at least :min characters.',
            'min.numeric'   => 'The :attribute must be at least :min.',
        ];
    }
}
