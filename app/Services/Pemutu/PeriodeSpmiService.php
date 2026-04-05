<?php

namespace App\Services\Pemutu;

use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Event\RapatService;
use Illuminate\Support\Facades\DB;

class PeriodeSpmiService
{
    public function __construct(
        protected RapatService $rapatService,
    ) {}

    /**
     * Resolve PeriodeSpmi by ID.
     */
    public function getById(string|int $id): PeriodeSpmi
    {
        return PeriodeSpmi::findOrFail(decryptIdIfEncrypted($id));
    }

    /**
     * Cari periode tahun lalu dengan jenis yang sama.
     */
    public function getPreviousPeriod(PeriodeSpmi $periode): ?PeriodeSpmi
    {
        $prevYear = (int) $periode->periode - 1;

        return PeriodeSpmi::where('periode', $prevYear)
            ->where('jenis_periode', $periode->jenis_periode)
            ->first();
    }

    /**
     * Ambil semua periode sebagai Collection — untuk card grid view.
     */
    public function getAll(?int $year = null)
    {
        $query = PeriodeSpmi::query();

        if ($year) {
            $query->where('periode', $year);
        }

        return $query->orderBy('periode', 'desc')
            ->orderBy('jenis_periode', 'asc')
            ->get();
    }

    /**
     * Ambil daftar tahun yang tersedia di sistem.
     */
    public function getAvailableYears()
    {
        $startYear = (int) config('pemutu.spmi_start_year', 2021);
        $endYear = (int) date('Y') + 1;

        $years = [];
        for ($y = $endYear; $y >= $startYear; $y--) {
            $years[] = $y;
        }

        return collect($years);
    }

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

    /**
     * Resolve the active Siklus SPMI year and return both Akademik & Non Akademik periodes.
     * Reads from session `siklus_spmi_tahun`, falls back to the latest year available.
     *
     * @return array{tahun: int, years: \Illuminate\Support\Collection, akademik: ?PeriodeSpmi, non_akademik: ?PeriodeSpmi}
     */
    public function getSiklusData(): array
    {
        try {
            $years = $this->getAvailableYears();

            if ($years->isEmpty()) {
                return [
                    'tahun' => (int) date('Y'),
                    'years' => collect(),
                    'akademik' => null,
                    'non_akademik' => null,
                ];
            }

            $tahun = (int) (session('siklus_spmi_tahun') ?? $years->first());

            // Ensure the session year is valid
            if (! $years->contains($tahun)) {
                $tahun = $years->first();
                session(['siklus_spmi_tahun' => $tahun]);
            }

            $periodes = PeriodeSpmi::where('periode', $tahun)->get();

            return [
                'tahun' => $tahun,
                'years' => $years,
                'akademik' => $periodes->firstWhere('jenis_periode', 'Akademik'),
                'non_akademik' => $periodes->firstWhere('jenis_periode', 'Non Akademik'),
            ];
        } catch (\Exception $e) {
            // Fallback if any error occurs
            return [
                'tahun' => (int) date('Y'),
                'years' => collect([(int) date('Y')]),
                'akademik' => null,
                'non_akademik' => null,
            ];
        }
    }

    /**
     * Buat RTM (Rapat Tinjauan Manajemen) baru untuk satu Periode SPMI.
     */
    public function createRtm(PeriodeSpmi $periode, string $type, array $data): Rapat
    {
        return DB::transaction(function () use ($periode, $type, $data) {
            // 1. Buat rapat baru
            $rapat = $this->rapatService->store([
                'jenis_rapat' => "RTM {$type}",
                'judul_kegiatan' => "RTM {$type} Periode " . $periode->periode,
                'tgl_rapat' => $data['tgl_rapat'],
                'waktu_mulai' => $data['waktu_mulai'],
                'waktu_selesai' => $data['waktu_selesai'],
                'tempat_rapat' => $data['tempat_rapat'],
                'ketua_user_id' => isset($data['ketua_user_id']) ? decryptIdIfEncrypted($data['ketua_user_id']) : null,
                'notulen_user_id' => isset($data['notulen_user_id']) ? decryptIdIfEncrypted($data['notulen_user_id']) : null,
                'author_user_id' => auth()->id(),
            ]);

            // 2. Link ke PeriodeSpmi via event_rapat_entitas
            RapatEntitas::create([
                'rapat_id' => $rapat->rapat_id,
                'model' => 'PeriodeSpmi',
                'model_id' => $periode->periodespmi_id,
                'keterangan' => "RTM {$type} Periode " . $periode->periode,
            ]);

            // 3. Agendas Default (berdasarkan tipe)
            $agendas = [];
            if ($type === 'Pengendalian') {
                $agendas = [
                    'Hasil AMI',
                    'Umpan Balik',
                    'Kinerja Proses dan Kesesuaian Produk',
                    'Status Tindakan Pencegahan dan Perbaikan',
                    'Tindak Lanjut dari Tinjauan Sebelumnya',
                    'Perubahan yang Dapat Mempengaruhi Sistem Manajemen Mutu',
                ];
            } elseif ($type === 'Peningkatan') {
                $agendas = ['Rangkuman', 'Penggunaan Budget'];
            }

            foreach ($agendas as $i => $judul) {
                $this->rapatService->addAgenda($rapat, [
                    'judul_agenda' => $judul,
                    'isi' => '',
                    'seq' => $i + 1,
                ]);
            }

            logActivity('pemutu', "Membuat RTM {$type} untuk Periode {$periode->periode}");

            return $rapat;
        });
    }

    /**
     * Update data umum RTM.
     */
    public function updateRtm(Rapat $rapat, array $data): Rapat
    {
        return $this->rapatService->update($rapat, $data);
    }

    /**
     * Check if a specific phase is currently open for a given year and group.
     *
     * @param  string  $phase  Phase name (e.g., 'penetapan', 'pelaksanaan', 'ed', 'ami', 'pengendalian', 'peningkatan')
     * @param  int  $year  The cycle year (e.g., 2024)
     * @param  string|null  $kelompok  'Akademik' or 'Non Akademik'. Default to current session.
     * @return bool
     */
    public function isPhaseOpen(string $phase, int $year, ?string $kelompok = null): bool
    {
        if (! $kelompok) {
            $kelompok = session('pemutu_active_kelompok', 'akademik');
        }

        // Normalize kelompok for database comparison
        $kelompok = str_replace('_', ' ', ucwords($kelompok, '_'));
        if ($kelompok === 'Akademik') { $kelompok = 'Akademik'; }
        elseif ($kelompok === 'Non Akademik') { $kelompok = 'Non Akademik'; }

        $periode = PeriodeSpmi::where('periode', $year)
            ->where('jenis_periode', $kelompok)
            ->first();

        // Default to TRUE if no record is found, allowing flexibility if periods are not yet configured
        if (! $periode) {
            return true;
        }

        $startAttr = "{$phase}_awal";
        $endAttr = "{$phase}_akhir";

        $start = $periode->$startAttr;
        $end = $periode->$endAttr;

        if (! $start || ! $end) {
            return true;
        }

        $now = now()->startOfDay();
        return $now->between($start->startOfDay(), $end->endOfDay());
    }

    /**
     * Specifically check if the 'Penetapan' phase is open.
     */
    public function isPenetapanOpen(int $year, ?string $kelompok = null): bool
    {
        return $this->isPhaseOpen('penetapan', $year, $kelompok);
    }
}
