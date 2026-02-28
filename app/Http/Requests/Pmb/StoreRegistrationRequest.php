<?php
namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class StoreRegistrationRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'periode_id' => decryptId($this->periode_id),
            'jalur_id'   => decryptId($this->jalur_id),
        ]);
    }

    public function rules()
    {
        return [
            'periode_id'       => 'required|exists:pmb_periode,periode_id',
            'jalur_id'         => 'required|exists:pmb_jalur,jalur_id',
            'pilihan_prodi'    => 'required|array|min:1|max:2',
            'pilihan_prodi.*'  => 'required|exists:struktur_organisasi,orgunit_id',
            'nik'              => 'required|string|size:16',
            'no_hp'            => 'required|string|max:20',
            'tempat_lahir'     => 'required|string|max:255',
            'tanggal_lahir'    => 'required|date',
            'jenis_kelamin'    => 'required|in:L,P',
            'alamat_lengkap'   => 'required|string',
            'asal_sekolah'     => 'required|string|max:255',
            'nama_ibu_kandung' => 'required|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'periode_id'       => 'Periode',
            'jalur_id'         => 'Jalur',
            'pilihan_prodi'    => 'Pilihan Program Studi',
            'pilihan_prodi.*'  => 'Pilihan Program Studi',
            'nik'              => 'NIK',
            'no_hp'            => 'Nomor HP',
            'tempat_lahir'     => 'Tempat Lahir',
            'tanggal_lahir'    => 'Tanggal Lahir',
            'jenis_kelamin'    => 'Jenis Kelamin',
            'alamat_lengkap'   => 'Alamat Lengkap',
            'asal_sekolah'     => 'Asal Sekolah',
            'nama_ibu_kandung' => 'Nama Ibu Kandung',
        ];
    }
}
