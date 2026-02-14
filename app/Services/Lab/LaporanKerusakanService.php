<?php
namespace App\Services\Lab;

use App\Models\Lab\LaporanKerusakan;
use Exception;
use Illuminate\Support\Facades\DB;

class LaporanKerusakanService
{
    public function getFilteredQuery(array $filters)
    {
        $query = LaporanKerusakan::with(['inventaris.lab', 'createdBy'])
            ->latest('created_at'); // Using created_at as tanggal_lapor

        if (filled($filters['search']['value'] ?? null)) {
            $search = $filters['search']['value'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('inventaris', function ($i) use ($search) {
                    $i->where('nama_alat', 'like', "%{$search}%")
                        ->orWhereHas('lab', function ($l) use ($search) {
                            $l->where('name', 'like', "%{$search}%");
                        });
                })
                    ->orWhere('deskripsi_kerusakan', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function getLaporanById($id)
    {
        return LaporanKerusakan::with(['inventaris.lab', 'createdBy', 'teknisi'])->find($id);
    }

    public function createLaporan(array $data): LaporanKerusakan
    {
        return DB::transaction(function () use ($data) {
            $laporan = LaporanKerusakan::create([
                // 'lab_id' ignored as it is derived from inventaris
                'inventaris_id'       => $data['inventaris_id'],
                // pelapor is handled by Blameable trait (created_by)
                'deskripsi_kerusakan' => $data['deskripsi_kerusakan'],
                'status'              => 'open',
                'foto_sebelum'        => $data['bukti_foto'] ?? null,
            ]);

            logActivity('laporan_kerusakan', "Melaporkan kerusakan baru: {$data['deskripsi_kerusakan']}");

            return $laporan;
        });
    }

    public function updateStatus($id, $status, $teknisiNote = null)
    {
        $laporan = $this->getLaporanById($id);
        if (! $laporan) {
            throw new Exception('Laporan tidak ditemukan');
        }

        return DB::transaction(function () use ($laporan, $status, $teknisiNote) {
            $updateData = [
                'status'            => $status,
                'teknisi_id'        => auth()->id(),
                'catatan_perbaikan' => $teknisiNote,
            ];

            $laporan->update($updateData);

            logActivity('laporan_kerusakan', "Update status laporan ID {$id} menjadi {$status}");
            return $laporan;
        });
    }
}
