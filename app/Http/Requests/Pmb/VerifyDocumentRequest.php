<?php
namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class VerifyDocumentRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status'     => 'required|in:Verified,Rejected',
            'keterangan' => 'nullable|string|max:500',
        ];
    }
}
