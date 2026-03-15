<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PegawaiRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama'        => 'required|string|max:100',
            'email'       => 'nullable|email|max:100',
            'jenis'       => 'nullable|string|max:20',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'        => 'Nama',
            'email'       => 'Email',
            'org_unit_id' => 'Unit Organisasi',
            'jenis'       => 'Jenis',
        ];
    }
}
