<?php
namespace App\Http\Requests\Hr;

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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('pegawai'); // Get ID from route for updates

        // Note: Unique checks need to be against 'hr_riwayat_datadiri', not 'hr_pegawai'
        // But since we store latest ID in hr_pegawai, it's a bit complex.
        // For simplicity, we assume we check against the riwayat table.
        // On update, we ignore rows where pegawai_id = $id.

        $rules = [
            'nama'                  => 'required|string|max:255',
            'email'                 => 'nullable|email|max:255', // Validating unique email across latest records is tricky in SQL validation rule
            'nip'                   => 'nullable|string|max:20',
            'inisial'               => 'nullable|string|max:10',

            'orgunit_posisi_id'     => 'nullable|exists:hr_org_unit,org_unit_id',
            'orgunit_departemen_id' => 'nullable|exists:hr_org_unit,org_unit_id',
            'orgunit_prodi_id'      => 'nullable|exists:hr_org_unit,org_unit_id',

            // 'statuspegawai_id' => 'required|exists:hr_status_pegawai,statuspegawai_id',
            // 'statusaktifitas_id' => 'required|exists:hr_status_aktifitas,statusaktifitas_id',
        ];

        return $rules;
    }
}
