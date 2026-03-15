<?php

namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class JenisLayananPeriodeStoreRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'tahun_ajaran' => 'nullable|string',
            'semester' => 'nullable|string',
        ];
    }

    /**
     *
     * @return array<string, string>
     */
}
