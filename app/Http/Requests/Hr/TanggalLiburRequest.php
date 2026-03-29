<?php

namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class TanggalLiburRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'entries' => 'required|array',
            'entries.*.dates' => 'required', // String with multiple dates
            'entries.*.keterangan' => 'required|string',
            'tahun' => 'required|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'entries' => 'Data Entry',
            'entries.*.dates' => 'Tanggal Libur',
            'entries.*.keterangan' => 'Keterangan',
            'tahun' => 'Tahun',
        ];
    }
}
