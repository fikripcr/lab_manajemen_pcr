<?php
namespace App\Services\Cbt;

use App\Models\Cbt\KomposisiPaket;
use App\Models\Cbt\PaketUjian;
use Illuminate\Support\Facades\DB;

class PaketUjianService
{
    public function getFilteredQuery(array $filters = [])
    {
        return PaketUjian::with('pembuat')->latest();
    }

    public function store(array $data)
    {
        $paket = PaketUjian::create($data);
        logActivity('cbt', "Membuat paket ujian: {$paket->nama_paket}", $paket);
        return $paket;
    }

    public function update(PaketUjian $paket, array $data)
    {
        $paket->update($data);
        logActivity('cbt', "Memperbarui paket ujian: {$paket->nama_paket}", $paket);
        return $paket;
    }

    public function delete(PaketUjian $paket)
    {
        logActivity('cbt', "Menghapus paket ujian: {$paket->nama_paket}", $paket);
        return $paket->delete();
    }

    public function addSoal(PaketUjian $paket, array $soalIds)
    {
        return DB::transaction(function () use ($paket, $soalIds) {
            foreach ($soalIds as $soalId) {
                KomposisiPaket::firstOrCreate([
                    'paket_id' => $paket->paket_ujian_id,
                    'soal_id'  => decryptId($soalId),
                ]);
            }

            $paket->update(['total_soal' => $paket->komposisi()->count()]);
            logActivity('cbt', "Menambahkan " . count($soalIds) . " soal ke paket: {$paket->nama_paket}", $paket);
            return $paket;
        });
    }

    public function removeSoal(KomposisiPaket $komposisi)
    {
        return DB::transaction(function () use ($komposisi) {
            $paket = $komposisi->paket;
            $komposisi->delete();
            $paket->update(['total_soal' => $paket->komposisi()->count()]);
            logActivity('cbt', "Menghapus soal dari paket: {$paket->nama_paket}", $paket);
            return true;
        });
    }
}
