<?php

namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class VerifySingleDocumentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'dokumen_id' => 'required|exists:pmb_dokumen_upload,dokumenupload_id',
            'status' => 'required|in:Valid,Pending',
        ];
    }

    public function attributes(): array
    {
        return [
            'dokumen_id' => 'Dokumen',
            'status' => 'Status Dokumen',
        ];
    }
}
