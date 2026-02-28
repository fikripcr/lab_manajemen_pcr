<?php
namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class StoreJenisDokumenRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_dokumen' => 'required|string|max:255',
            'tipe_file'    => 'nullable|string|max:255',
            'max_size_kb'  => 'required|integer|min:1',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_dokumen' => 'Nama Dokumen',
            'tipe_file'    => 'Tipe File',
            'max_size_kb'  => 'Ukuran Maksimal (KB)',
        ];
    }
}
