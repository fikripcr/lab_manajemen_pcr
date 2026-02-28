<?php
namespace App\Http\Requests\Survei;

use App\Http\Requests\BaseRequest;

class PertanyaanRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $mergeData = [];

        // Decrypt the ID if it's sent as a HashID
        if ($this->has('halaman_id')) {
            $mergeData['halaman_id'] = decryptIdIfEncrypted($this->input('halaman_id'));
        }

        if ($this->has('next_pertanyaan_id')) {
            $val                             = $this->input('next_pertanyaan_id');
            $mergeData['next_pertanyaan_id'] = (! empty($val)) ? decryptIdIfEncrypted($val) : null;
        }

        if ($this->has('opsi') && is_array($this->input('opsi'))) {
            $opsi = $this->input('opsi');
            foreach ($opsi as $key => $o) {
                if (is_array($o)) {
                    if (isset($o['id']) && ! empty($o['id'])) {
                        $opsi[$key]['id'] = decryptIdIfEncrypted($o['id']);
                    }
                    if (isset($o['next_pertanyaan_id'])) {
                        $v                                = $o['next_pertanyaan_id'];
                        $opsi[$key]['next_pertanyaan_id'] = (! empty($v)) ? decryptIdIfEncrypted($v) : null;
                    }
                }
            }
            $mergeData['opsi'] = $opsi;
        }

        if (! empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'halaman_id'                => 'sometimes|required|exists:survei_halaman,halaman_id',
            'tipe'                      => 'sometimes|required|in:Teks_Singkat,Esai,Angka,Pilihan_Ganda,Kotak_Centang,Dropdown,Skala_Linear,Tanggal,Upload_File,Rating_Bintang',
            'teks_pertanyaan'           => 'sometimes|required|string',
            'bantuan_teks'              => 'nullable|string',
            'wajib_diisi'               => 'nullable|boolean',
            'urutan'                    => 'nullable|integer',
            'next_pertanyaan_id'        => 'nullable|exists:survei_pertanyaan,pertanyaan_id',
            'config_json'               => 'nullable|array',
            'opsi'                      => 'nullable|array',
            'opsi.*.id'                 => 'nullable|exists:survei_opsi,opsi_id',
            'opsi.*.label'              => 'nullable|string',
            'opsi.*.next_pertanyaan_id' => 'nullable|exists:survei_pertanyaan,pertanyaan_id',
        ];
    }

    public function attributes(): array
    {
        return [
            'halaman_id'                => 'Halaman',
            'tipe'                      => 'Tipe Pertanyaan',
            'teks_pertanyaan'           => 'Teks Pertanyaan',
            'bantuan_teks'              => 'Teks Bantuan',
            'wajib_diisi'               => 'Wajib Diisi',
            'urutan'                    => 'Urutan',
            'next_pertanyaan_id'        => 'Lompat ke Pertanyaan',
            'config_json'               => 'Konfigurasi Tambahan',
            'opsi'                      => 'Pilihan Opsi',
            'opsi.*.label'              => 'Label Opsi',
            'opsi.*.next_pertanyaan_id' => 'Lompat ke Pertanyaan (Opsi)',
        ];
    }
}
