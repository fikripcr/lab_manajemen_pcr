<?php
namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisLayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'nama_layanan'                    => [
                'required',
                'string',
                'max:255',
                Rule::unique('eoffice_jenis_layanan', 'nama_layanan')->ignore($id, 'jenislayanan_id')->whereNull('deleted_at'),
            ],
            'kategori'                        => 'required|string|in:layanan,umum,akademik,keuangan,sdm,sarpras',
            'keterangan'                      => 'nullable|string',
            'bidang_terkait'                  => 'nullable|string',
            'batas_pengerjaan'                => 'required|integer|min:0',
            'only_show_on'                    => 'nullable|array',
            'is_fitur_diskusi'                => 'nullable|boolean',
            'is_fitur_keterlibatan'           => 'nullable|boolean',
            'is_fitur_keterlibatan_pegawai'   => 'nullable|boolean',
            'is_fitur_keterlibatan_mahasiswa' => 'nullable|boolean',
            'is_fitur_disposisi'              => 'nullable|boolean',
            'is_fitur_feedback'               => 'nullable|boolean',
            'is_active'                       => 'required|boolean',
            'file_template'                   => 'nullable|file|mimes:docx|max:2048',
        ];
    }

    protected function prepareForValidation()
    {
        $booleans = [
            'is_fitur_diskusi', 'is_fitur_keterlibatan', 'is_fitur_keterlibatan_pegawai',
            'is_fitur_keterlibatan_mahasiswa', 'is_fitur_disposisi', 'is_fitur_feedback',
            'is_active',
        ];

        foreach ($booleans as $field) {
            if ($this->has($field)) {
                $this->merge([$field => (bool) $this->input($field)]);
            } else if ($field !== 'is_active') {
                $this->merge([$field => false]);
            }
        }
    }
}
