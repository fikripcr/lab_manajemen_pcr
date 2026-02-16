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
            ['jadwal_id' => $jadwal->id, 'user_id' => $user->id],
            [
                'waktu_mulai'  => now(),
                'status'       => 'Sedang_Mengerjakan',
                'ip_address'   => $requestData['ip'],
                'browser_info' => $requestData['user_agent'],
            ]
        );
    }

    public function saveAnswer(RiwayatUjianSiswa $riwayat, array $data)
    {
        return JawabanSiswa::updateOrCreate(
            ['riwayat_id' => $riwayat->id, 'soal_id' => $data['soal_id']],
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
                    if ($kunci && $kunci->id === $jw->opsi_dipilih_id) {
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

            // 3. Integration with PMB
            $pendaftaran = Pendaftaran::where('user_id', $riwayat->user_id)->latest()->first();
            if ($pendaftaran) {
                $pendaftaran->update(['status_terkini' => 'Selesai_Ujian']);
                $peserta = PesertaUjian::where('pendaftaran_id', $pendaftaran->id)->first();
                if ($peserta) {
                    $peserta->update(['nilai_akhir' => $totalNilai]);
                }
            }

            return $riwayat;
        });
    }
}
