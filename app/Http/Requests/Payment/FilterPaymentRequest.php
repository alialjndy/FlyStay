<?php

namespace App\Http\Requests\Payment;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilterPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user && $user->hasAnyRole(['finance_officer','admin']);
        }catch(Exception){
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
            'status'      => 'nullable|in:pending,completed,failed,refunded',
            'method'      => 'nullable|in:cash,stripe',
            'date'        => 'nullable|date',
            'amount'      => 'nullable|numeric|min:0',
            'from_date'   => 'nullable|date|required_with:to_date',
            'to_date'     => 'nullable|date|after_or_equal:from_date|required_with:from_date',
            'object_type' => 'nullable|string',
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
            'status'      => 'Status Booking',
            'from_date'   => 'From Date',
            'to_date'     => 'To Date',
            'amount'      => 'Amount',
            'method'      => 'Payment Method',
            'object_type' => 'Object Type',

        ];
    }
    public function messages(){
        return [
            'status.in'          => 'The selected booking status is invalid. Please choose from: pending, completed, failed, refunded.',
            'method.in'          => 'The selected payment method is invalid. Please choose from: cash, stripe.',
            'from_date.date'     => 'The start date must be a valid date.',
            'from_date.required_with' => 'The start date field is required when end date is present.',
            'to_date.date'       => 'The end date must be a valid date.',
            'to_date.after_or_equal' => 'The end date must be equal to or after the start date.',
            'to_date.required_with'   => 'The end date field is required when start date is present.',
            'amount.numeric'     => 'The amount must be a numeric value.',
            'amount.min'         => 'The amount must be at least 0.',
            'object_type.string' => 'The object type must be a string value.',
        ];
    }

}
