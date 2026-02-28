<?php
namespace App\Http\Requests\Shared;

use App\Http\Requests\BaseRequest;

class PersonilRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): bool
    {
        $id = $this->route('personil');
        if (is_string($id)) {
            $id = decryptIdIfEncrypted($id);
        }

        return [
            'nama'        => 'required|string|max:100',
            'email'       => 'nullable|email|max:100|unique:personil,email,' . $id . ',personil_id',
            'nip'         => 'nullable|string|max:50|unique:personil,nip,' . $id . ',personil_id',
            'posisi'      => 'nullable|string|max:191',
            'tipe'        => 'nullable|string|max:30',
            'vendor'      => 'nullable|string|max:191',
            'org_unit_id' => 'nullable|exists:struktur_organisasi,orgunit_id',
        ];
    }
}
