<?php

namespace App\Http\Requests\HotelBooking;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateHotelBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user && $user->hasAnyRole(['customer', 'hotel_agent']);
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
            'check_in_date'=>'nullable|date|after_or_equal:today',
            'check_out_date'=>'nullable|date|after:check_in_date',
        ];
    }
    public function withValidator(Validator $validator){
        $validator->after(function($validator){
            $user = JWTAuth::parseToken()->authenticate();
            $hotelBooking = $this->route('hotel_booking');
            if(!$this->check_in_date && !$this->check_out_date){
                $validator->errors()->add('dates', 'At least one date (check-in or check-out) must be provided.');
            }
            if($user->id != $hotelBooking->user_id){
                $validator->errors()->add('authorization', 'You are not authorized to update this booking.');
            }
        });
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
            'check_in_date' => 'check-in date',
            'check_out_date' => 'check-out date',
        ];
    }
    public function messages(){
        return [
            'required'       => 'The :attribute field is required.',
            'date'           => 'The :attribute must be a valid date.',
            'after_or_equal' => 'The :attribute must be today or in the future.',
            'after'          => 'The :attribute must be after the check-in date.',
        ];
    }
}
