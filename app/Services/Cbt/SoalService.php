<?php
namespace App\Services\Cbt;

use App\Models\Cbt\OpsiJawaban;
use App\Models\Cbt\Soal;
use Illuminate\Support\Facades\DB;

class SoalService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $soalData = [
                'mata_uji_id'       => $data['mata_uji_id'],
                'tipe_soal'         => $data['tipe_soal'],
                'konten_pertanyaan' => $data['konten_pertanyaan'],
                'tingkat_kesulitan' => $data['tingkat_kesulitan'],
                'is_aktif'          => true,
                'dibuat_oleh'       => auth()->id(),
            ];

            $soal = Soal::create($soalData);

            if ($data['tipe_soal'] == 'Pilihan_Ganda' && isset($data['opsi'])) {
                foreach ($data['opsi'] as $label => $teks) {
                    OpsiJawaban::create([
                        'soal_id'          => $soal->soal_id,
                        'label'            => $label,
                        'teks_jawaban'     => $teks,
                        'is_kunci_jawaban' => ($data['kunci_jawaban'] == $label),
                        'bobot_nilai'      => ($data['kunci_jawaban'] == $label ? 1 : 0),
                    ]);
                }
            }

            if ($data['tipe_soal'] == 'Benar_Salah') {
                foreach (['Benar', 'Salah'] as $val) {
                    OpsiJawaban::create([
                        'soal_id'          => $soal->soal_id,
                        'label'            => $val,
                        'teks_jawaban'     => $val,
                        'is_kunci_jawaban' => ($data['kunci_jawaban'] == $val),
                        'bobot_nilai'      => ($data['kunci_jawaban'] == $val ? 1 : 0),
                    ]);
                }
            }

            return $soal;
        });
    }

    public function update(Soal $soal, array $data)
    {
        return DB::transaction(function () use ($soal, $data) {
            $soal->update([
                'mata_uji_id'       => $data['mata_uji_id'],
                'tipe_soal'         => $data['tipe_soal'],
                'konten_pertanyaan' => $data['konten_pertanyaan'],
                'tingkat_kesulitan' => $data['tingkat_kesulitan'],
            ]);

            if ($data['tipe_soal'] == 'Pilihan_Ganda' && isset($data['opsi'])) {
                $soal->opsiJawaban()->delete();
                foreach ($data['opsi'] as $label => $teks) {
                    OpsiJawaban::create([
                        'soal_id'          => $soal->soal_id,
                        'label'            => $label,
                        'teks_jawaban'     => $teks,
                        'is_kunci_jawaban' => ($data['kunci_jawaban'] == $label),
                        'bobot_nilai'      => ($data['kunci_jawaban'] == $label ? 1 : 0),
                    ]);
                }
            }

            if ($data['tipe_soal'] == 'Benar_Salah') {
                $soal->opsiJawaban()->delete();
                foreach (['Benar', 'Salah'] as $val) {
                    OpsiJawaban::create([
                        'soal_id'          => $soal->soal_id,
                        'label'            => $val,
                        'teks_jawaban'     => $val,
                        'is_kunci_jawaban' => ($data['kunci_jawaban'] == $val),
                        'bobot_nilai'      => ($data['kunci_jawaban'] == $val ? 1 : 0),
                    ]);
                }
            }

            return $soal;
        });
    }

    public function delete(Soal $soal)
    {
        return $soal->delete();
    }
}
