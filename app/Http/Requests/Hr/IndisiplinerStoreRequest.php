<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class IndisiplinerStoreRequest extends FormRequest
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
            'jenisindisipliner_id' => 'required|exists:hr_jenis_indisipliner,jenisindisipliner_id',
            'tgl_indisipliner' => 'required|date',
            'pegawai_id' => 'required|array|min:1',
            'pegawai_id.*' => 'exists:hr_pegawai,pegawai_id',
            'keterangan' => 'nullable|string|max:1000',
            'bukti' => 'nullable|file|mimes:pdf,docx,doc,jpg,jpeg,png|max:5120',
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
            'jenisindisipliner_id.required' => 'Jenis indisipliner harus dipilih.',
            'jenisindisipliner_id.exists' => 'Jenis indisipliner tidak ditemukan.',
            'tgl_indisipliner.required' => 'Tanggal indisipliner harus diisi.',
            'tgl_indisipliner.date' => 'Tanggal indisipliner harus berupa tanggal.',
            'pegawai_id.required' => 'Pegawai harus dipilih.',
            'pegawai_id.array' => 'Pegawai harus berupa array.',
            'pegawai_id.min' => 'Minimal pilih 1 pegawai.',
            'pegawai_id.*.exists' => 'Pegawai tidak ditemukan.',
            'keterangan.string' => 'Keterangan harus berupa string.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
            'bukti.file' => 'Bukti harus berupa file.',
            'bukti.mimes' => 'File harus berformat PDF, DOCX, DOC, JPG, JPEG, atau PNG.',
            'bukti.max' => 'File maksimal 5MB.',
        ];
    }
}
