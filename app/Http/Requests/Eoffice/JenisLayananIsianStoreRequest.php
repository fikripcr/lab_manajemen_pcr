<?php
namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;

class JenisLayananIsianStoreRequest extends FormRequest
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
            'kategoriisian_id' => 'required|exists:eoffice_kategori_isian,kategoriisian_id',
            'seq'              => 'required|integer',
            'is_required'      => 'nullable|boolean',
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
            'kategoriisian_id.required' => 'Kategori isian harus dipilih.',
            'kategoriisian_id.exists'   => 'Kategori isian tidak ditemukan.',
            'seq.required'              => 'Urutan harus diisi.',
            'seq.integer'               => 'Urutan harus berupa angka.',
            'is_required.boolean'       => 'Required harus true atau false.',
            'nama_field.required'       => 'Nama field harus diisi.',
            'nama_field.string'         => 'Nama field harus berupa string.',
            'nama_field.max'            => 'Nama field maksimal 255 karakter.',
            'tipe_field.required'       => 'Tipe field harus dipilih.',
            'tipe_field.in'             => 'Tipe field tidak valid.',
            'placeholder.string'        => 'Placeholder harus berupa string.',
            'placeholder.max'           => 'Placeholder maksimal 255 karakter.',
            'options.string'            => 'Options harus berupa string.',
            'validasi.string'           => 'Validasi harus berupa string.',
        ];
    }
}
