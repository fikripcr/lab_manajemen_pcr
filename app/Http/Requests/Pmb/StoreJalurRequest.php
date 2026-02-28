<?php
namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class StoreJalurRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_jalur'        => 'required|string|max:255',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'is_aktif'          => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_aktif' => $this->has('is_aktif'),
        ]);
    }
}
