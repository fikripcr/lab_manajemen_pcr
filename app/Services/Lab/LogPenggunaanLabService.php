<?php
namespace App\Services\Lab;

use App\Models\Lab\LogPenggunaanLab;
use Illuminate\Support\Facades\DB;

class LogPenggunaanLabService
{
    public function getFilteredQuery(array $filters)
    {
        $query = LogPenggunaanLab::with(['lab', 'kegiatan'])
            ->latest('waktu_isi');

        if (filled($filters['search']['value'] ?? null)) {
            $search = $filters['search']['value'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('lab', function ($l) use ($search) {
                    $l->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('kegiatan', function ($k) use ($search) {
                        $k->where('nama_kegiatan', 'like', "%{$search}%");
                    })
                    ->orWhere('nama_peserta', 'like', "%{$search}%")
                    ->orWhere('npm_peserta', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function createLog(array $data): LogPenggunaanLab
    {
        return DB::transaction(function () use ($data) {
            // If kegiatan_id is provided, ensure it's valid active event
            if (! empty($data['kegiatan_id'])) {
                // validation logic if needed
            }

            $log = LogPenggunaanLab::create([
                'kegiatan_id'   => $data['kegiatan_id'] ?? null,
                'lab_id'        => $data['lab_id'],
                'nama_peserta'  => $data['nama_peserta'],
                'email_peserta' => $data['email_peserta'] ?? null,
                'npm_peserta'   => $data['npm_peserta'] ?? null,
                'waktu_isi'     => now(),
                'nomor_pc'      => $data['nomor_pc'] ?? null,
                'kondisi'       => $data['kondisi'] ?? 'Baik',
                'catatan_umum'  => $data['catatan_umum'] ?? null,
            ]);

            return $log;
        });
    }
}
