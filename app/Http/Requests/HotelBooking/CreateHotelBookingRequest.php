<?php

namespace App\Http\Requests\HotelBooking;

use App\Models\Room;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateHotelBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user && $user->hasAnyRole(['customer', 'hotel_agent','admin']);
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
        $user = JWTAuth::parseToken()->authenticate();
        return [
            'user_id'=>$user->hasRole('customer') ? 'nullable|exists:users,id' : 'required|exists:users,id',
            'room_id'=>'required|exists:rooms,id',
            'check_in_date'=>'required|date|after_or_equal:today',
            'check_out_date'=>'required|date|after:check_in_date',
        ];
    }
    public function withValidator(Validator $validator){
        $validator->after(function($validator){
            $room = Room::find($this->room_id);
            if(!$room->isAvailable($this->check_in_date , $this->check_out_date)){
                $validator->errors()->add('room_id','Room is not available.');
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
            'user_id'        => 'User Id',
            'room_id'        => 'Room Id',
            'check_in_date'  => 'check-in date',
            'check_out_date' => 'check-out date',
        ];
    }
    public function messages(){
        return [
            'required'          => 'The :attribute field is required.',
            'exists'            => 'The selected :attribute is invalid.',
            'date'              => 'The :attribute must be a valid date.',
            'after_or_equal'    => 'The :attribute must be today or in the future.',
            'after'             => 'The :attribute must be after the check-in date.',
            'user_id.required'  => 'Please select a guest for the booking.',
            'user_id.exists'    => 'The selected guest does not exist.',
            'room_id.required'  => 'Please select a room.',
            'room_id.exists'    => 'The selected room does not exist.'
        ];
    }
}
