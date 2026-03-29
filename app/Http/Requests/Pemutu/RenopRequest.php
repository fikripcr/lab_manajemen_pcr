<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class RenopRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'indikator' => 'required|string',
            'target' => 'required|string',
            'parent_id' => 'nullable|exists:pemutu_indikator,indikator_id',
            'type' => 'required|in:renop',
        ];
    }

    public function attributes(): array
    {
        return [
            'indikator' => 'Indikator',
            'target' => 'Target',
            'parent_id' => 'Indikator Induk',
            'seq' => 'Urutan',
            'type' => 'Tipe',
        ];
    }
}
