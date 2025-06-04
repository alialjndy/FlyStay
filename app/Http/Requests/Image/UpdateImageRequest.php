<?php

namespace App\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateImageRequest extends FormRequest
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
            'new_photos'        =>'nullable|array|min:1|max:10',
            'new_photos.*'      =>'file|mimes:jpg,jpeg,png,webp,gif|max:2048',

            'deleted_photos'    =>'nullable|array|min:1|max:10',
            'deleted_photos.*'  =>'integer|exists:images,id'
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
            'new_photos'        => 'New image files',
            'new_photos.*'      => 'New image file',

            'deleted_photos'    =>'Delete image files',
            'deleted_photos.*'  =>'Delete image file',
        ];
    }
    public function messages(){
        return [
            // 'images.required' => 'You must upload at least one image.',
            'new_photos.array'        => 'Images must be sent as an array.',
            'new_photos.*.mimes'      => 'Each image must be a valid format: jpg, jpeg, png, webp, gif.',
            'new_photos.*.max'        => 'Each image must not exceed 2MB.',

            'deleted_photos.array'    => 'Images must be sent as an array.',
            'deleted_photos.*.integer'  => 'Each deleted photo ID must be an integer.',
            'deleted_photos.*.exists'    => 'One or more images you want to delete do not exist.',

            'exists'=>'The :attribute field is invalid',
        ];
    }
}
