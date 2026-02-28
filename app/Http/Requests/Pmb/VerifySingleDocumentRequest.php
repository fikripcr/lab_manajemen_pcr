<?php

namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class VerifySingleDocumentRequest extends BaseRequest
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
            'dokumen_id' => 'required|exists:pmb_dokumen_upload,dokumenupload_id',
            'status' => 'required|in:Valid,Pending',
        ];
    }
}
