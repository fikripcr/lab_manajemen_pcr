<?php

namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class VerifyDocumentBatchRequest extends BaseRequest
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
     */
    public function rules(): array
    {
        return [
            'dokumen_ids' => 'required|array',
            'status' => 'required|in:Valid,Ditolak',
            'catatan' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'dokumen_ids' => 'Dokumen IDs',
            'status'      => 'Status Verifikasi',
            'catatan'     => 'Catatan Verifikasi',
        ];
    }
}
