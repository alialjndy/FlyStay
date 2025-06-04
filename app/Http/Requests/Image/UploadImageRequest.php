<?php

namespace App\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadImageRequest extends FormRequest
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
            'images'   => 'nullable|array|min:1|max:10',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp,gif|max:2048',
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
            'images'   => 'Image files',
            'images.*' => 'Image file'
        ];
    }
    public function messages(){
        return [
            // 'images.required' => 'You must upload at least one image.',
            'images.array'    => 'Images must be sent as an array.',
            'images.*.mimes'  => 'Each image must be a valid format: jpg, jpeg, png, webp, gif.',
            'images.*.max'    => 'Each image must not exceed 2MB.',
        ];
    }
}
