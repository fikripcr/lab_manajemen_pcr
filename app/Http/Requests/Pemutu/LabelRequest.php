<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class LabelRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|exists:pemutu_label,label_id',
            'name' => 'required|string|max:100',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'parent_id' => 'Parent Label',
            'name' => 'Nama Label',
            'slug' => 'Slug',
            'color' => 'Warna Label',
            'description' => 'Deskripsi',
        ];
    }
}
