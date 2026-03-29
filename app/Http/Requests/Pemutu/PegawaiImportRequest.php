<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PegawaiImportRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:xlsx,xls,csv',
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => 'File Excel',
        ];
    }
}
