<?php
namespace App\Http\Requests\Pmb;

use Illuminate\Foundation\Http\FormRequest;

class VerifyDocumentRequest extends FormRequest
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
