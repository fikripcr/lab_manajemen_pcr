<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PresensiUploadPhotoRequest extends BaseRequest
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
            'photo'         => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'face_encoding' => 'nullable|string',
        ];
    }
}
