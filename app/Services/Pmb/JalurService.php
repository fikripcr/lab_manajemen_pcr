<?php
namespace App\Services\Pmb;

use App\Models\Pmb\Jalur;

class JalurService
{
    public function getPaginateData(array $filters = [])
    {
        $query = Jalur::query();

        if (! empty($filters['search']['value'])) {
            $search = $filters['search']['value'];
            $query->where('nama_jalur', 'like', "%{$search}%");
        }

        return $query->latest();
    }

    public function createJalur(array $data): Jalur
    {
        $jalur = Jalur::create($data);
        logActivity('pmb_jalur', "Menambahkan jalur baru: {$jalur->nama_jalur}", $jalur);
        return $jalur;
    }

    public function updateJalur(Jalur $jalur, array $data): bool
    {
        $jalur->update($data);
        logActivity('pmb_jalur', "Memperbarui jalur: {$jalur->nama_jalur}", $jalur);
        return true;
    }

    public function deleteJalur(Jalur $jalur): bool
    {
        $nama = $jalur->nama_jalur;
        $jalur->delete();
        logActivity('pmb_jalur', "Menghapus jalur: {$nama}");
        return true;
    }

    public function getAllActive()
    {
        return Jalur::where('is_aktif', true)->get();
    }
}
