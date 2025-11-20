<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PengumumanRequest extends FormRequest
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
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'jenis' => ['required', 'in:pengumuman,berita'],
            'is_published' => ['boolean'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // max 2MB
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,xls,xlsx', 'max:5120'], // max 5
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul wajib diisi.',
            'judul.string' => 'Judul harus berupa teks.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',

            'isi.required' => 'Isi wajib diisi.',
            'isi.string' => 'Isi harus berupa teks.',

            'jenis.required' => 'Jenis wajib dipilih.',
            'jenis.in' => 'Jenis yang dipilih tidak valid.',

            'is_published.boolean' => 'Status publikasi harus berupa benar atau salah.',

            'cover.image' => 'Cover harus berupa gambar.',
            'cover.mimes' => 'Cover harus berupa file JPEG, PNG, JPG, atau GIF.',
            'cover.max' => 'Ukuran cover tidak boleh lebih dari 2MB.',

            'attachments.*.file' => 'Lampiran harus berupa file.',
            'attachments.*.mimes' => 'Lampiran harus berupa file PDF, DOC, DOCX, ZIP, XLS, atau XLSX.',
            'attachments.*.max' => 'Ukuran lampiran tidak boleh lebih dari 5MB.',
        ];
    }
}
