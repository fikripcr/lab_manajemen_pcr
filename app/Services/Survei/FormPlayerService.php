<?php
namespace App\Services\Survei;

use App\Models\Survei\Jawaban;
use App\Models\Survei\Pengisian;
use App\Models\Survei\Survei;
use Exception;
use Illuminate\Support\Facades\DB;

class FormPlayerService
{
    /**
     * Get survey structure for player.
     */
    public function getSurveyForPlayer(Survei $survei): Survei
    {
        return $survei->load([
            'halaman'            => fn($q)            => $q->orderBy('urutan'),
            'halaman.pertanyaan' => fn($q) => $q->orderBy('urutan'),
            'halaman.pertanyaan.opsi',
        ]);
    }

    /**
     * Handle survey submission.
     */
    public function submitSurvey(Survei $survei, array $jawaban, string $ipAddress)
    {
        return DB::transaction(function () use ($survei, $jawaban, $ipAddress) {
            // 1. Create Pengisian Record
            $pengisian = Pengisian::create([
                'survei_id'     => $survei->id,
                'user_id'       => auth()->id(),
                'status'        => 'Selesai',
                'waktu_mulai'   => now(),
                'waktu_selesai' => now(),
                'ip_address'    => $ipAddress,
            ]);

            // 2. Prepare Answers
            $validPertanyaan = $survei->pertanyaan()->pluck('tipe', 'pertanyaan_id');
            $answerRows      = [];
            $now             = now();

            foreach ($jawaban as $pertanyaanId => $nilai) {
                if (! $validPertanyaan->has($pertanyaanId)) {
                    continue;
                }

                $tipe = $validPertanyaan[$pertanyaanId];
                $row  = [
                    'pengisian_id'  => $pengisian->id,
                    'pertanyaan_id' => $pertanyaanId,
                    'opsi_id'       => null,
                    'nilai_teks'    => null,
                    'nilai_angka'   => null,
                    'nilai_json'    => null,
                    'nilai_tanggal' => null,
                    'dibuat_pada'   => $now,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];

                // Map value to correct column based on type
                if (is_array($nilai)) {
                    $row['nilai_json'] = json_encode($nilai);
                } elseif (in_array($tipe, ['Angka', 'Skala_Linear', 'Rating_Bintang'])) {
                    $row['nilai_angka'] = (int) $nilai;
                } elseif ($tipe == 'Tanggal') {
                    $row['nilai_tanggal'] = $nilai;
                } elseif (in_array($tipe, ['Pilihan_Ganda', 'Dropdown'])) {
                    if (is_numeric($nilai)) {
                        $row['opsi_id'] = $nilai;
                    } else {
                        $row['nilai_teks'] = $nilai;
                    }
                } else {
                    $row['nilai_teks'] = $nilai;
                }

                $answerRows[] = $row;
            }

            // 3. Batch Insert
            if (! empty($answerRows)) {
                Jawaban::insert($answerRows);
            }

            return $pengisian;
        });
    }

    /**
     * Validate if survey is accessible.
     */
    public function validateAccessibility(Survei $survei)
    {
        if ($survei->tanggal_mulai && now()->lt($survei->tanggal_mulai)) {
            throw new Exception('Survei belum dimulai.');
        }
        if ($survei->tanggal_selesai && now()->gt($survei->tanggal_selesai)) {
            throw new Exception('Survei sudah berakhir.');
        }

        if ($survei->wajib_login && ! auth()->check()) {
            throw new Exception('AUTH_REQUIRED');
        }

        if (! $survei->bisa_isi_ulang && auth()->check()) {
            $hasFilled = Pengisian::where('survei_id', $survei->id)
                ->where('user_id', auth()->id())
                ->exists();
            if ($hasFilled) {
                throw new Exception('ALREADY_FILLED');
            }
        }
    }
}
