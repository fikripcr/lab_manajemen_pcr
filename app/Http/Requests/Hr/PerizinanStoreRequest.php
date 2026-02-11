<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class PerizinanStoreRequest extends FormRequest
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
            'jenisizin_id' => 'required|exists:hr_jenis_izin,jenisizin_id',
            'pengusul' => 'required|exists:hr_pegawai,pegawai_id',
            'pekerjaan_ditinggalkan' => 'nullable|string|max:500',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'keterangan' => 'nullable|string|max:1000',
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
            'jenisizin_id.required' => 'Jenis izin harus dipilih.',
            'jenisizin_id.exists' => 'Jenis izin tidak ditemukan.',
            'pengusul.required' => 'Pengusul harus dipilih.',
            'pengusul.exists' => 'Pengusul tidak ditemukan.',
            'pekerjaan_ditinggalkan.string' => 'Pekerjaan yang ditinggalkan harus berupa string.',
            'pekerjaan_ditinggalkan.max' => 'Pekerjaan yang ditinggalkan maksimal 500 karakter.',
            'tgl_mulai.required' => 'Tanggal mulai harus diisi.',
            'tgl_mulai.date' => 'Tanggal mulai harus berupa tanggal.',
            'tgl_selesai.required' => 'Tanggal selesai harus diisi.',
            'tgl_selesai.date' => 'Tanggal selesai harus berupa tanggal.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'keterangan.string' => 'Keterangan harus berupa string.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
        ];
    }
}
