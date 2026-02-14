<?php
namespace App\Http\Requests\Lab;

use Illuminate\Foundation\Http\FormRequest;

class LabInventarisStoreRequest extends FormRequest
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
            'inventaris_id' => 'required|exists:lab_inventaris,inventaris_id',
            'no_series'     => 'nullable|string|max:255',
            'keterangan'    => 'nullable|string|max:1000',
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
            'inventaris_id.required' => 'Inventaris harus dipilih.',
            'inventaris_id.exists'   => 'Inventaris tidak ditemukan.',
            'no_series.string'       => 'Nomor seri harus berupa string.',
            'no_series.max'          => 'Nomor seri maksimal 255 karakter.',
            'keterangan.string'      => 'Keterangan harus berupa string.',
            'keterangan.max'         => 'Keterangan maksimal 1000 karakter.',
        ];
    }
}
