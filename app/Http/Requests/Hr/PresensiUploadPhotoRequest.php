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
            'face_encoding' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'photo'         => 'Foto Presensi',
            'face_encoding' => 'Face Encoding',
        ];
    }
}
