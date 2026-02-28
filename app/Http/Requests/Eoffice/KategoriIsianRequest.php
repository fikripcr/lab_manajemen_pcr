<?php
namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class KategoriIsianRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'nama_isian'        => [
                'required',
                'string',
                'max:255',
                Rule::unique('eoffice_kategori_isian', 'nama_isian')->ignore($id, 'kategoriisian_id')->whereNull('deleted_at'),
            ],
            'type'              => 'required|string|in:text,number,date,daterange,select,select_api,textarea,file',
            'type_value'        => 'required_if:type,select|nullable|array',
            'alias_on_document' => 'nullable|string|max:100',
            'keterangan_isian'  => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        //
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'nama_isian.required'    => 'Nama isian wajib diisi.',
            'nama_isian.unique'      => 'Nama isian sudah digunakan.',
            'type.required'          => 'Tipe isian wajib dipilih.',
            'type_value.required_if' => 'Opsi pilihan wajib diisi untuk tipe select.',
        ]);
    }
}
