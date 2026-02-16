<?php
namespace App\Http\Requests\Pmb;

use Illuminate\Foundation\Http\FormRequest;

class StoreJenisDokumenRequest extends FormRequest
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
}
