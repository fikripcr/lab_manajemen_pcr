<?php
namespace App\Services\Pemutu;

use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Event\RapatService;
use Illuminate\Support\Facades\DB;

class PeningkatanService
{
    public function __construct(
        protected RapatService $RapatService,
    ) {}

    /**
     * Default RTM Peningkatan agendas.
     */
    public const DEFAULT_AGENDAS = [
        'Rangkuman',
        'Penggunaan Budget',
    ];

    /**
     * Buat RTM Peningkatan baru untuk satu Periode SPMI.
     * Otomatis membuat Rapat + menghubungkan via RapatEntitas + insert agenda default.
     */
    public function createRtm(PeriodeSpmi $periode, array $data): Rapat
    {
        return DB::transaction(function () use ($periode, $data) {
            // 1. Buat rapat baru
            $rapat = $this->RapatService->store([
                'jenis_rapat'     => 'RTM Peningkatan',
                'judul_kegiatan'  => 'RTM Peningkatan Periode ' . $periode->periode,
                'tgl_rapat'       => $data['tgl_rapat'],
                'waktu_mulai'     => $data['waktu_mulai'],
                'waktu_selesai'   => $data['waktu_selesai'],
                'tempat_rapat'    => $data['tempat_rapat'],
                'ketua_user_id'   => $data['ketua_user_id'] ?? null,
                'notulen_user_id' => $data['notulen_user_id'] ?? null,
                'author_user_id'  => auth()->id(),
            ]);

            // 2. Link ke PeriodeSpmi via event_rapat_entitas
            RapatEntitas::create([
                'rapat_id'   => $rapat->rapat_id,
                'model'      => 'PeriodeSpmi',
                'model_id'   => $periode->periodespmi_id,
                'keterangan' => 'RTM Peningkatan Periode ' . $periode->periode,
            ]);

            // 3. Insert default agendas
            foreach (self::DEFAULT_AGENDAS as $i => $judul) {
                $this->RapatService->addAgenda($rapat, [
                    'judul_agenda' => $judul,
                    'isi'          => '',
                    'seq'          => $i + 1,
                ]);
            }

            logActivity('pemutu', "Membuat RTM Peningkatan untuk Periode {$periode->periode}");

            return $rapat;
        });
    }

    /**
     * Update data umum RTM Peningkatan (waktu, tempat, pejabat).
     */
    public function updateRtm(Rapat $rapat, array $data): Rapat
    {
        return $this->RapatService->update($rapat, $data);
    }
}
