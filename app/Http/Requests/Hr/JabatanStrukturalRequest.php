<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class JabatanStrukturalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:hr_jabatan_struktural,jabatan_struktural_id',
            'is_active' => 'boolean',
        ];
    }
}
