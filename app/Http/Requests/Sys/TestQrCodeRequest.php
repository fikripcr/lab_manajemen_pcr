<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class TestQrCodeRequest extends BaseRequest
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
            'text' => 'required|string|max:500',
            'size' => 'required|integer|min:100|max:500',
        ];
    }

    /**
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'text' => 'Teks QR Code',
            'size' => 'Ukuran QR Code',
        ];
    }
}
