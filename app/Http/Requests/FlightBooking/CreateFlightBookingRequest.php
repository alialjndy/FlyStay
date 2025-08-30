<?php

namespace App\Http\Requests\FlightBooking;

use App\Models\FlightCabin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateFlightBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user && $user->hasAnyRole(['customer', 'flight_agent','admin']);
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
            'user_id'=> $user->hasRole('customer') ? 'nullable|exists:users,id' : 'required|exists:users,id',
            'flight_cabins_id'=>'required|exists:flight_cabins,id',
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
    public function withValidator(Validator $validator){
        $validator->after(function($validator){
            $flightCabin = FlightCabin::find($this->input('flight_cabins_id'));

            if (!$flightCabin) {
                return;
            }
            $flight = $flightCabin->flight ;

            if($flight->departure_time < now()){
                $validator->errors()->add('flight_cabins_id', 'Booking is not possible because the flight has already departed');
            }
            if($flightCabin->available_seats <= 0){
                $validator->errors()->add('flight_cabins_id', 'No available seats in this cabin class');
            }
        });
    }
    public function attributes(){
        return [
            'user_id' => 'User Id',
            'flight_cabins_id' => 'Flight Details',
        ];
    }
    public function messages(){
        return [
            'required' => 'The :attribute field is required.',
            'exists' => 'The selected :attribute does not exist.',
            'user_id.required' => 'Please select a passenger for the booking.',
            'user_id.exists' => 'The selected passenger does not exist.',
            'flight_cabins_id.required' => 'Please select a flight cabin.',
            'flight_cabins_id.exists' => 'The selected flight cabin does not exist.'
        ];
    }
}
