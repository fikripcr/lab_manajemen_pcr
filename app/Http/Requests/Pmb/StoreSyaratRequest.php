<?php
namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class StoreSyaratRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'jalur_id'         => decryptId($this->jalur_id),
            'jenis_dokumen_id' => decryptId($this->jenis_dokumen_id),
            'is_required'      => $this->has('is_required'),
        ]);
    }

    public function rules()
    {
        return [
            'jalur_id'         => 'required|exists:pmb_jalur,id',
            'jenis_dokumen_id' => 'required|exists:pmb_jenis_dokumen,id',
            'is_required'      => 'boolean',
        ];
    }
}
