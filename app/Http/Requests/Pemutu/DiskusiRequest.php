<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class DiskusiRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'isi'             => ['required', 'string'],
            'jenis_pengirim'  => ['required', 'in:auditor,auditee'],
            'attachment_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'isi'             => 'Isi Diskusi',
            'attachment_file' => 'File Lampiran',
        ];
    }
}
