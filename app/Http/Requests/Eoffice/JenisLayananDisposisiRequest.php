<?php

namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class JenisLayananDisposisiRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'model' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
}
