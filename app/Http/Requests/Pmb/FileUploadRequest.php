<?php
namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class FileUploadRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'pendaftaran_id'   => decryptId($this->pendaftaran_id),
            'jenis_dokumen_id' => decryptId($this->jenis_dokumen_id),
        ]);
    }

    public function rules()
    {
        return [
            'pendaftaran_id'   => 'required|exists:pmb_pendaftaran,id',
            'jenis_dokumen_id' => 'required|exists:pmb_jenis_dokumen,id',
            'file'             => 'required|file|max:5120',
        ];
    }

    public function attributes(): array
    {
        return [
            'pendaftaran_id'   => 'Pendaftaran',
            'jenis_dokumen_id' => 'Jenis Dokumen',
            'file'             => 'File',
        ];
    }
}
