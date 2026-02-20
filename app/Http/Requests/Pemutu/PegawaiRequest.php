<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama'        => 'required|string|max:100',
            'email'       => 'nullable|email|max:100',
            'org_unit_id' => 'nullable|exists:org_unit,orgunit_id',
            'jenis'       => 'nullable|string|max:20',
        ];
    }
}
