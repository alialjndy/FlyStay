<?php

namespace App\Http\Requests\HotelBooking;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilterHotelBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user && $user->hasAnyRole(['admin', 'hotel_agent']);
        }catch(Exception $e){
            return false ;
        }
    }
    protected function failedAuthorization(){
        throw new AuthorizationException('you cannot perform this action.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status'=>'nullable|string',
            'booking_date'=>'nullable|date',
            'from_date'=>'nullable|date',
            'to_date'=>'nullable|date'
        ];
    }
    public function failedValidation(Validator $validator){
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
            'status'=>'Booking status',
            'booking_date'=>'Booking Date',
            'from_date'=>'From Date',
            'to_date'=>'To Date'
        ];
    }
    public function messages(){
        return[
            'string'=> 'The :attribute field value must be a string',
            'date'=> 'The :attribute filed value must a valid date'
        ];
    }
}
