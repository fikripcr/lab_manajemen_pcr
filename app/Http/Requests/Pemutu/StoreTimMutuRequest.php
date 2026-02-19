<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimMutuRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'auditee_id'       => ['nullable', 'exists:pegawai,pegawai_id'],
            'ketua_auditor_id' => ['nullable', 'exists:pegawai,pegawai_id'],
            'auditor_ids'      => ['nullable', 'array'],
            'auditor_ids.*'    => ['nullable', 'exists:pegawai,pegawai_id'],
            'anggota_ids'      => ['nullable', 'array'],
            'anggota_ids.*'    => ['nullable', 'exists:pegawai,pegawai_id'],
        ];
    }
}
