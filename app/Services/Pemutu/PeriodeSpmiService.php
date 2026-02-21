<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\PeriodeSpmi;
use Illuminate\Support\Facades\DB;

class PeriodeSpmiService
{
    public function store(array $data): PeriodeSpmi
    {
        return DB::transaction(function () use ($data) {
            $periode = PeriodeSpmi::create($data);
            logActivity('pemutu', "Menambah periode SPMI: {$periode->periode}", $periode);
            return $periode;
        });
    }

    public function update(PeriodeSpmi $periode, array $data): PeriodeSpmi
    {
        return DB::transaction(function () use ($periode, $data) {
            $periode->update($data);
            logActivity('pemutu', "Mengupdate periode SPMI: {$periode->periode}", $periode);
            return $periode;
        });
    }

    public function delete(PeriodeSpmi $periode): void
    {
        DB::transaction(function () use ($periode) {
            logActivity('pemutu', "Menghapus periode SPMI: {$periode->periode}", $periode);
            $periode->delete();
        });
    }
}
