<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FilterRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hotel_name'=>'nullable|string',
            'min_price'=>'nullable|numeric|min:0',
            'max_price'=>'nullable|numeric|min:0',
            'capacity'=>'nullable|integer',
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        throw new HttpResponseException(
            response()->json([
                'status'=>'failed',
                'message'=>'Validation failed. Please check your input values.',
                'errors' => $validator->errors()
            ],422)
            );
    }
    /**
     * Add custom logical validation
     * @param mixed $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $min = $this->input('min_price');
            $max = $this->input('max_price');

            if (!is_null($min) && !is_null($max) && $min > $max) {
                $validator->errors()->add('max_price', 'The maximum price must be greater than or equal to the minimum price.');
            }
        });
    }
    public function attributes(){
        return [
            'hotel_name' => 'hotel name',
            'min_price'  => 'minimum price',
            'max_price'  => 'maximum price',
            'capacity'   => 'room capacity',
        ];
    }
    public function messages(){
        return [
            'hotel_name.string' => 'The :attribute must be a valid string.',
            'min_price.numeric' => 'The :attribute must be a valid number.',
            'max_price.numeric' => 'The :attribute must be a valid number.',
            'min_price.min'     => 'The :attribute must be at least 0.',
            'max_price.min'     => 'The :attribute must be at least 0.',
            'capacity.integer'  => 'The :attribute must be a valid integer.',
        ];
    }
}
