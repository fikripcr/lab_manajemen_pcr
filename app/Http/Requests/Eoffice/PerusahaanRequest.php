<?php
namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerusahaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'kategoriperusahaan_id' => 'required|exists:eoffice_kategori_perusahaan,kategoriperusahaan_id',
            'nama_perusahaan'       => [
                'required',
                'string',
                'max:255',
                Rule::unique('eoffice_perusahaan', 'nama_perusahaan')->ignore($id, 'perusahaan_id')->whereNull('deleted_at'),
            ],
            'alamat'                => 'nullable|string',
            'kota'                  => 'nullable|string|max:100',
            'telp'                  => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'kategoriperusahaan_id.required' => 'Kategori perusahaan wajib dipilih.',
            'nama_perusahaan.required'       => 'Nama perusahaan wajib diisi.',
            'nama_perusahaan.unique'         => 'Nama perusahaan sudah ada.',
        ];
    }
}
