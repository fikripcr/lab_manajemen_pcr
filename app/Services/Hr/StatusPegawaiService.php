<?php
namespace App\Services\Hr;

use App\Models\Hr\StatusPegawai;
use Illuminate\Support\Facades\DB;

class StatusPegawaiService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $statusPegawai = StatusPegawai::create($data);
            logActivity('hr', "Menambahkan status pegawai: {$statusPegawai->nama_status}", $statusPegawai);
            return $statusPegawai;
        });
    }

    public function update(StatusPegawai $statusPegawai, array $data)
    {
        return DB::transaction(function () use ($statusPegawai, $data) {
            $statusPegawai->update($data);
            logActivity('hr', "Memperbarui status pegawai: {$statusPegawai->nama_status}", $statusPegawai);
            return $statusPegawai;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $statusPegawai = StatusPegawai::findOrFail($id);
            logActivity('hr', "Menghapus status pegawai: {$statusPegawai->nama_status}", $statusPegawai);
            $statusPegawai->delete();
        });
    }
}
