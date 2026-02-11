<?php

namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;

class LayananStatusUpdateRequest extends FormRequest
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
            'status_layanan' => 'required|string',
            'keterangan' => 'nullable|string',
            'file_lampiran' => 'nullable|file|mimes:pdf,docx,zip,jpg,png|max:5120',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status_layanan.required' => 'Status layanan harus diisi.',
            'status_layanan.string' => 'Status layanan harus berupa string.',
            'keterangan.string' => 'Keterangan harus berupa string.',
            'file_lampiran.file' => 'File lampiran harus berupa file.',
            'file_lampiran.mimes' => 'File harus berformat PDF, DOCX, ZIP, JPG, atau PNG.',
            'file_lampiran.max' => 'File maksimal 5MB.',
        ];
    }
}
