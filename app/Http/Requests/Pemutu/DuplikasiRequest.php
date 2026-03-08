<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class DuplikasiRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'target_periode'     => ['required', 'integer', 'min:2020', 'max:2099'],
            'selected_dok_ids'   => ['required', 'array', 'min:1'],
            'selected_dok_ids.*' => ['required', 'integer', 'exists:pemutu_dokumen,dok_id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'target_periode'     => 'Periode Target',
            'selected_dok_ids'   => 'Standar Terpilih',
            'selected_dok_ids.*' => 'ID Standar',
        ];
    }
}
