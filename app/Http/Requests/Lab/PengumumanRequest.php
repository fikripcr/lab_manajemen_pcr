<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class PengumumanRequest extends BaseRequest
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
            'judul'         => ['required', 'string', 'max:255'],
            'isi'           => ['required', 'string'],
            'jenis'         => ['required', 'in:pengumuman,berita'],
            'is_published'  => ['boolean'],
            'cover'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],         // max 2MB
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
        return array_merge(parent::messages(), [
            'cover.image'          => 'Cover harus berupa gambar.',
            'cover.mimes'          => 'Cover harus berupa file JPEG, PNG, JPG, atau GIF.',
            'cover.max'            => 'Ukuran cover tidak boleh lebih dari 2MB.',

            'attachments.*.file'   => 'Lampiran harus berupa file.',
            'attachments.*.mimes'  => 'Lampiran harus berupa file PDF, DOC, DOCX, ZIP, XLS, atau XLSX.',
        ]);
    }

    public function attributes(): array
    {
        return [
            'judul'         => 'Judul',
            'isi'           => 'Isi',
            'jenis'         => 'Jenis',
            'is_published'  => 'Status Publikasi',
            'cover'         => 'Cover',
            'attachments'   => 'Lampiran',
            'attachments.*' => 'Lampiran',
        ];
    }
}
