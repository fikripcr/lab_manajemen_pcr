<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InventarisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lab_id' => ['required', 'exists:labs,lab_id'],
            'nama_alat' => ['required', 'string', 'max:255'],
            'jenis_alat' => ['required', 'string', 'max:255'],
            'kondisi_terakhir' => ['required', 'string', 'max:255'],
            'tanggal_pengecekan' => ['required', 'date'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return validation_messages_id();
    }
}