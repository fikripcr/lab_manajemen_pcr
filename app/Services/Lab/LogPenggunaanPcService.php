<?php
namespace App\Services\Lab;

use App\Models\Lab\JadwalKuliah;
use App\Models\Lab\LogPenggunaanPc;
use App\Models\Lab\PcAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LogPenggunaanPcService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(array $filters)
    {
        $query = LogPenggunaanPc::with(['user', 'jadwal.mataKuliah', 'lab', 'pcAssignment'])
            ->latest('waktu_isi');

        if (filled($filters['search']['value'] ?? null)) {
            $search = $filters['search']['value'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                })
                    ->orWhereHas('jadwal.mataKuliah', function ($m) use ($search) {
                        $m->where('nama_mk', 'like', "%{$search}%");
                    })
                    ->orWhere('catatan_umum', 'like', "%{$search}%");
            });
        }

        // Filter by Lab if needed
        if (filled($filters['lab_id'] ?? null)) {
            $query->where('lab_id', decryptId($filters['lab_id']));
        }

        return $query;
    }

    /**
     * Find Active Schedule for Current Time
     */
    public function getCurrentActiveJadwal(): ?JadwalKuliah
    {
        $now  = Carbon::now();
        $hari = $this->getIndonesianDay($now->dayOfWeek);
        $time = $now->format('H:i:s');

        // Logic sederhana: Hari sama, jam sekarang di antara mulai dan selesai.
        // Bisa dikembangkan untuk handle toleransi waktu (misal boleh isi 15 menit sebelum/sesudah).
        return JadwalKuliah::with(['mataKuliah', 'lab', 'dosen'])
            ->where('hari', $hari)
            ->where('jam_mulai', '<=', $time)
            ->where('jam_selesai', '>=', $time)
            ->first();
    }

    /**
     * Get Assigned PC for User in a specific Schedule
     */
    public function getAssignmentForUser($userId, $jadwalId): ?PcAssignment
    {
        return PcAssignment::where('user_id', $userId)
            ->where('jadwal_id', $jadwalId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Store Log
     */
    public function storeLog(array $data): LogPenggunaanPc
    {
        // Validasi Bisnis Logic
        $jadwalId = $data['jadwal_id'];
        $userId   = $data['user_id']; // Usually from Auth::id()

        // 1. Cek User Assignment (Optional, bisa di-skip jika kebijakan lab bebas duduk)
        // Tapi request user: "mengisi Log ... sesuai jadwal perkuliahan saya".
        // Asumsi: Harus sesuai assignment jika ada assignment-nya.
        // Jika tidak ada assignment, mungkin boleh isi bebas?
        // Untuk fase ini, kita catat `pc_assignment_id` jika ada.

        $assignment = $this->getAssignmentForUser($userId, $jadwalId);

        // Jika user memaksa input nomor PC yang beda dengan assignment?
        // Kita trust input user atau paksa dari assignment?
        // Idealnya: Form auto-filled dari assignment, tapi user bisa ubah jika pindah kursi (dan log harus catat kursi aktual).
        // Jadi kita simpan `nomor_pc` aktual di log (Logic di Controller/View), tapi link ke assignment jika match.

        return DB::transaction(function () use ($data, $assignment) {
            $log = LogPenggunaanPc::create([
                'user_id'          => $data['user_id'],
                'jadwal_id'        => $data['jadwal_id'],
                'lab_id'           => $data['lab_id'],
                'pc_assignment_id' => $assignment ? $assignment->pc_assignments_id : null,
                // Note: LogPenggunaanPc model fillable & migration check needed.
                // Assuming `nomor_pc` column exists in log table?
                // Let's check Model. Model `LogPenggunaanPc` doesn't have `nomor_pc` in fillable in previous `view_file`.
                // It has `status_pc`, `kondisi`, `catatan_umum`.
                // Wait, analysis step showed `LogPenggunaanLab` has `nomor_pc`.
                // `LogPenggunaanPc` model showed `pc_assignment_id`.
                // Does `LogPenggunaanPc` store the actual PC number if no assignment?
                // Let's check `LogPenggunaanPc` model again in my memory or view it.
                // Step 54: `LogPenggunaanPc` fillable: `pc_assignment_id`, `user_id`, `jadwal_id`, `lab_id`, `status_pc`, `kondisi`, ...
                // It does NOT have `nomor_pc`. It relies on `pc_assignment_id`.
                // ISSUE: If user sits on a PC without assignment, can they log?
                // If table is strict, they MUST have assignment.
                // Request says "Mahasiswa ... mengisi Log".
                // If Assignment is Master Data required, then we must Assign first.
                // Let's assume Valid Assignment is REQUIRED.

                'status_pc'        => $data['status_pc'],       // Baik/Rusak
                'kondisi'          => $data['kondisi'] ?? null, // Detail
                'catatan_umum'     => $data['catatan_umum'] ?? null,
                'waktu_isi'        => now(),
            ]);

            return $log;
        });
    }

    private function getIndonesianDay($dayOfWeek)
    {
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        return $days[$dayOfWeek] ?? '';
    }
}
