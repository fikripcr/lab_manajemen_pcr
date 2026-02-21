<?php
namespace App\Services\Pmb;

use App\Models\Pmb\JenisDokumen;

class JenisDokumenService
{
    public function getPaginateData(array $filters = [])
    {
        $query = JenisDokumen::query();

        if (! empty($filters['search']['value'])) {
            $search = $filters['search']['value'];
            $query->where('nama_dokumen', 'like', "%{$search}%");
        }

        return $query->latest();
    }

    public function createJenisDokumen(array $data): JenisDokumen
    {
        $jenisDokumen = JenisDokumen::create($data);
        logActivity('pmb_jenis_dokumen', "Menambahkan jenis dokumen baru: {$jenisDokumen->nama_dokumen}", $jenisDokumen);
        return $jenisDokumen;
    }

    public function updateJenisDokumen(JenisDokumen $jenisDokumen, array $data): bool
    {
        $jenisDokumen->update($data);
        logActivity('pmb_jenis_dokumen', "Memperbarui jenis dokumen: {$jenisDokumen->nama_dokumen}", $jenisDokumen);
        return true;
    }

    public function deleteJenisDokumen(JenisDokumen $jenisDokumen): bool
    {
        $nama = $jenisDokumen->nama_dokumen;
        $jenisDokumen->delete();
        logActivity('pmb_jenis_dokumen', "Menghapus jenis dokumen: {$nama}");
        return true;
    }

    public function getAll()
    {
        return JenisDokumen::all();
    }
}
