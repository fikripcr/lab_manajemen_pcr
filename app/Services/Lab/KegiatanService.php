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
                'dokumentasi_path' => $data['dokumentasi_path'] ?? null, // Surat permohonan etc
            ]);

            logActivity('peminjaman_lab', "Membuat booking baru: {$data['nama_kegiatan']}");

            return $kegiatan;
        });
    }

    public function updateStatus($id, $status, $catatan = null)
    {
        $kegiatan = Kegiatan::find($id);
        if (! $kegiatan) {
            throw new Exception('Booking tidak ditemukan');
        }

        return DB::transaction(function () use ($kegiatan, $status, $catatan) {
            $kegiatan->update([
                'status'      => $status,
                'catatan_pic' => $catatan,
            ]);

            logActivity('peminjaman_lab', "Update status booking ID {$id} menjadi {$status}");
            return $kegiatan;
        });
    }
}
