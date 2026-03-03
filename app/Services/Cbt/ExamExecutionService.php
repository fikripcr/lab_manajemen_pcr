<?php
namespace App\Services\Cbt;

use App\Models\Cbt\JadwalUjian;
use App\Models\Cbt\JawabanSiswa;
use App\Models\Cbt\RiwayatUjianSiswa;
use App\Models\Pmb\Pendaftaran;
use App\Models\Pmb\PesertaUjian;
use Illuminate\Support\Facades\DB;

class ExamExecutionService
{
    public function startExam(JadwalUjian $jadwal, $user, $requestData)
    {
        return RiwayatUjianSiswa::firstOrCreate(
            ['jadwal_id' => $jadwal->jadwal_ujian_id, 'user_id' => $user->id],
            [
                'waktu_mulai'  => now(),
                'status'       => 'Sedang_Mengerjakan',
                'ip_address'   => $requestData['ip'],
                'browser_info' => substr($requestData['user_agent'], 0, 255),
            ]
        );
    }

    public function saveAnswer(RiwayatUjianSiswa $riwayat, array $data)
    {
        return JawabanSiswa::updateOrCreate(
            ['riwayat_id' => $riwayat->riwayat_ujian_id, 'soal_id' => $data['soal_id']],
            [
                'opsi_dipilih_id' => $data['opsi_id'] ?? null,
                'jawaban_esai'    => $data['jawaban_esai'] ?? null,
                'is_ragu'         => $data['is_ragu'] ?? false,
            ]
        );
    }

    public function submitExam(RiwayatUjianSiswa $riwayat)
    {
        return DB::transaction(function () use ($riwayat) {
            if ($riwayat->status === 'Selesai') {
                return $riwayat;
            }

            // 1. Auto-grading (for Pilihan Ganda)
            $totalNilai = 0;
            $riwayat->load(['jawaban.soal.opsiJawaban']);

            foreach ($riwayat->jawaban as $jw) {
                if ($jw->soal->tipe_soal === 'Pilihan_Ganda' && $jw->opsi_dipilih_id) {
                    $kunci = $jw->soal->opsiJawaban->where('is_kunci_jawaban', true)->first();
                    if ($kunci && $kunci->opsi_jawaban_id === $jw->opsi_dipilih_id) {
                        $totalNilai += $kunci->bobot_nilai;
                        $jw->update(['nilai_didapat' => $kunci->bobot_nilai]);
                    }
                }
            }

            // 2. Update status and final score
            $riwayat->update([
                'status'        => 'Selesai',
                'waktu_selesai' => now(),
                'nilai_akhir'   => $totalNilai,
            ]);

            // 3. Integration with PMB (optional — only if user has active registration)
            try {
                $pendaftaran = Pendaftaran::where('user_id', $riwayat->user_id)->latest()->first();
                if ($pendaftaran) {
                    $pendaftaran->update(['status_terkini' => 'Sudah_Ujian']);
                    $peserta = PesertaUjian::where('pendaftaran_id', $pendaftaran->id)->first();
                    if ($peserta) {
                        $peserta->update(['nilai_akhir' => $totalNilai]);
                    }
                }
            } catch (\Exception $e) {
                // PMB integration failure should not block exam submission
                \Log::warning('CBT: PMB integration failed on submit', ['error' => $e->getMessage(), 'riwayat_id' => $riwayat->riwayat_ujian_id]);
            }

            return $riwayat;
        });
    }

    /**
     * Get monitoring statistics for dashboard
     */
    public function getMonitoringStats(): array
    {
        return [
            'active_exams'          => JadwalUjian::where('waktu_mulai', '<=', now())
                ->where('waktu_selesai', '>=', now())
                ->count(),
            'total_exams_today'     => JadwalUjian::whereDate('waktu_mulai', today())->count(),
            'students_taking_exam'  => RiwayatUjianSiswa::where('status', 'Sedang_Mengerjakan')->count(),
            'completed_exams_today' => RiwayatUjianSiswa::whereDate('waktu_selesai', today())->count(),
        ];
    }

    /**
     * Get active exams for dashboard
     */
    public function getActiveExams(): \Illuminate\Database\Eloquent\Collection
    {
        return JadwalUjian::with(['paket', 'riwayatSiswa.user'])
            ->where('waktu_mulai', '<=', now())
            ->where('waktu_selesai', '>=', now())
            ->get();
    }

    /**
     * Get recent violations for dashboard
     */
    public function getRecentViolations(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Cbt\LogPelanggaran::with(['riwayatUjianSiswa.user'])
            ->latest('waktu_kejadian')
            ->limit($limit)
            ->get();
    }

    /**
     * Get exam interface data for Camaba
     */
    public function getExamInterfaceData($userId): array
    {
        $pendaftaran = \App\Models\Pmb\Pendaftaran::with(['pesertaUjian.sesiUjian'])
            ->where('user_id', $userId)
            ->where('status_terkini', 'Siap_Ujian')
            ->first();

        $hasPendaftaran = (bool) $pendaftaran;
        $questions      = collect();
        $activeSessions = collect();
        $sesiUjian      = null;
        $paketUjian     = null;

        if ($hasPendaftaran) {
            $pesertaUjian = $pendaftaran->pesertaUjian;
            if ($pesertaUjian && $pesertaUjian->sesiUjian) {
                $sesiUjian  = $pesertaUjian->sesiUjian;
                $paketUjian = $sesiUjian->paket;

                if ($paketUjian) {
                    $questions = \App\Models\Cbt\KomposisiPaket::with(['soal.opsiJawaban'])
                        ->where('paket_id', $paketUjian->paket_ujian_id)
                        ->orderBy('urutan_tampil')
                        ->get();
                }
            }
        } else {
            $activeSessions = JadwalUjian::with('paket')
                ->where('waktu_selesai', '>=', now())
                ->orderBy('waktu_mulai')
                ->get();
        }

        return [
            'pendaftaran'    => $pendaftaran,
            'hasPendaftaran' => $hasPendaftaran,
            'questions'      => $questions,
            'activeSessions' => $activeSessions,
            'sesiUjian'      => $sesiUjian,
            'paketUjian'     => $paketUjian,
        ];
    }
}
