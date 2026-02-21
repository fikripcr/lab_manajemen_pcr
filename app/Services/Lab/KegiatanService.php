<?php
namespace App\Services\Lab;

use App\Models\Lab\Kegiatan;
use Exception;
use Illuminate\Support\Facades\DB;

class KegiatanService
{
    public function getFilteredQuery(array $filters)
    {
        $query = Kegiatan::with(['lab', 'penyelenggara'])
            ->latest('tanggal');

        if (filled($filters['search']['value'] ?? null)) {
            $search = $filters['search']['value'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('lab', function ($l) use ($search) {
                    $l->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('penyelenggara', function ($p) use ($search) {
                        $p->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('nama_kegiatan', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function createBooking(array $data): Kegiatan
    {
        return DB::transaction(function () use ($data) {
            // Validate availability (Simple overlapping check)
            $exists = Kegiatan::where('lab_id', $data['lab_id'])
                ->where('tanggal', $data['tanggal'])
                ->where(function ($q) use ($data) {
                    $q->whereBetween('jam_mulai', [$data['jam_mulai'], $data['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$data['jam_mulai'], $data['jam_selesai']])
                        ->orWhere(function ($sub) use ($data) {
                            $sub->where('jam_mulai', '<=', $data['jam_mulai'])
                                ->where('jam_selesai', '>=', $data['jam_selesai']);
                        });
                })
                ->where('status', '!=', 'rejected')
                ->exists();

            if ($exists) {
                throw new Exception("Lab tidak tersedia pada jam tersebut.");
            }

            $kegiatan = Kegiatan::create([
                'lab_id'           => $data['lab_id'],
                'penyelenggara_id' => auth()->id(),
                'nama_kegiatan'    => $data['nama_kegiatan'],
                'deskripsi'        => $data['deskripsi'],
                'tanggal'          => $data['tanggal'],
                'jam_mulai'        => $data['jam_mulai'],
                'jam_selesai'      => $data['jam_selesai'],
                'status'           => 'pending',
                'dokumentasi_path' => $data['dokumentasi_path'] ?? null,
            ]);

            // Create Initial Approval (Pending)
            $approval = \App\Models\Lab\LabRiwayatApproval::create([
                'model'      => Kegiatan::class,
                'model_id'   => $kegiatan->kegiatan_id,
                'status'     => 'pending',
                'keterangan' => 'Menunggu persetujuan',
                'created_by' => auth()->id(),
            ]);

            $kegiatan->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            logActivity('peminjaman_lab', "Membuat booking baru: {$data['nama_kegiatan']}");

            return $kegiatan;
        });
    }

    public function updateStatus(Kegiatan $kegiatan, $status, $catatan = null)
    {
        return DB::transaction(function () use ($kegiatan, $status, $catatan) {
            // Create New Approval Record
            $approval = \App\Models\Lab\LabRiwayatApproval::create([
                'model'      => Kegiatan::class,
                'model_id'   => $kegiatan->kegiatan_id,
                'status'     => $status,
                'pejabat'    => auth()->user()->name,
                'keterangan' => $catatan,
                'created_by' => auth()->id(),
            ]);

            $kegiatan->update([
                'status'                    => $status,
                'catatan_pic'               => $catatan, // Maintain legacy column for quick access if needed, or remove? Better keep for now.
                'latest_riwayatapproval_id' => $approval->riwayatapproval_id,
            ]);

            logActivity('peminjaman_lab', "Update status booking ID {$kegiatan->kegiatan_id} menjadi {$status}");
            return $kegiatan;
        });
    }
}
