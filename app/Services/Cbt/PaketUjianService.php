<?php
namespace App\Services\Cbt;

use App\Models\Cbt\KomposisiPaket;
use App\Models\Cbt\PaketUjian;
use Illuminate\Support\Facades\DB;

class PaketUjianService
{
    public function store(array $data)
    {
        return PaketUjian::create($data);
    }

    public function update(PaketUjian $paket, array $data)
    {
        $paket->update($data);
        return $paket;
    }

    public function delete(PaketUjian $paket)
    {
        return $paket->delete();
    }

    public function addSoal(PaketUjian $paket, array $soalIds)
    {
        return DB::transaction(function () use ($paket, $soalIds) {
            foreach ($soalIds as $soalId) {
                KomposisiPaket::firstOrCreate([
                    'paket_id' => $paket->id,
                    'soal_id'  => decryptId($soalId),
                ]);
            }

            $paket->update(['total_soal' => $paket->komposisi()->count()]);
            return $paket;
        });
    }

    public function removeSoal(KomposisiPaket $komposisi)
    {
        return DB::transaction(function () use ($komposisi) {
            $paket = $komposisi->paket;
            $komposisi->delete();
            $paket->update(['total_soal' => $paket->komposisi()->count()]);
            return true;
        });
    }
}
