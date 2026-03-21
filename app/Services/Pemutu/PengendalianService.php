<?php
namespace App\Services\Pemutu;

use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Event\RapatService;
use Illuminate\Support\Facades\DB;

class PengendalianService
{
    public function __construct(
        protected RapatService $RapatService,
    ) {}
    /**
     * Default RTM agendas.
     */
    public const DEFAULT_AGENDAS = [
        'Hasil AMI',
        'Umpan Balik',
        'Kinerja Proses dan Kesesuaian Produk',
        'Status Tindakan Pencegahan dan Perbaikan',
        'Tindak Lanjut dari Tinjauan Sebelumnya',
        'Perubahan yang Dapat Mempengaruhi Sistem Manajemen Mutu',
    ];

    /**
     * Ambil query Indikator untuk periode pengendalian.
     * Menggunakan IndikatorService (DRY) seperti ED dan AMI.
     */
    public function getIndikatorQuery(PeriodeSpmi $periode, ?int $unitId = null)
    {
        $filters = [
            'kelompok_indikator' => $periode->jenis,
            'tahun_dokumen'      => $periode->tahun,
        ];

        return app(IndikatorService::class)->getByOrgUnit($unitId, $filters);
    }

    /**
     * Simpan data pengendalian: status dan analisis.
     */
    public function submitPengendalian(IndikatorOrgUnit $indOrg, array $data): IndikatorOrgUnit
    {
        $payload = [
            'pengend_status'           => $data['pengend_status'],
            'pengend_analisis'         => $data['pengend_analisis'] ?? null,
            'pengend_important_matrix' => $data['pengend_important_matrix'] ?? null,
            'pengend_urgent_matrix'    => $data['pengend_urgent_matrix'] ?? null,
        ];

        // Initial Sync to Superior Columns (draft)
        $payload['pengend_status_atsn']           = $payload['pengend_status'];
        $payload['pengend_analisis_atsn']         = $payload['pengend_analisis'];
        $payload['pengend_important_matrix_atsn'] = $payload['pengend_important_matrix'];
        $payload['pengend_urgent_matrix_atsn']    = $payload['pengend_urgent_matrix'];

        $indOrg->update($payload);

        logActivity('pemutu', 'Submit pengendalian: indikator #' . $indOrg->indikator_id . ' unit #' . $indOrg->org_unit_id, $indOrg);

        return $indOrg;
    }

    /**
     * Update hanya Eisenhower Matrix (Important & Urgent) secara inline/AJAX.
     */
    public function updateMatrix(IndikatorOrgUnit $indOrg, array $data): IndikatorOrgUnit
    {
        $payload = [
            'pengend_important_matrix' => $data['pengend_important_matrix'] ?? null,
            'pengend_urgent_matrix'    => $data['pengend_urgent_matrix'] ?? null,
        ];

        // Also sync to superior columns
        $payload['pengend_important_matrix_atsn'] = $payload['pengend_important_matrix'];
        $payload['pengend_urgent_matrix_atsn']    = $payload['pengend_urgent_matrix'];

        $indOrg->update($payload);

        return $indOrg;
    }

    /**
     * Simpan validasi atasan: hanya kolom _atsn.
     */
    public function submitValidasi(IndikatorOrgUnit $indOrg, array $data): IndikatorOrgUnit
    {
        $payload = [
            'pengend_status_atsn'           => $data['pengend_status_atsn'],
            'pengend_analisis_atsn'         => $data['pengend_analisis_atsn'] ?? null,
            'pengend_important_matrix_atsn' => $data['pengend_important_matrix_atsn'] ?? null,
            'pengend_urgent_matrix_atsn'    => $data['pengend_urgent_matrix_atsn'] ?? null,
        ];

        $indOrg->update($payload);

        logActivity('pemutu', 'Validasi pengendalian atasan: indikator #' . $indOrg->indikator_id . ' unit #' . $indOrg->org_unit_id, $indOrg);

        return $indOrg;
    }

    /**
     * Buat RTM baru untuk satu Periode SPMI.
     * Otomatis membuat Rapat + menghubungkan via RapatEntitas + insert agenda default.
     */
    public function createRtm(PeriodeSpmi $periode, array $data): Rapat
    {
        return DB::transaction(function () use ($periode, $data) {
            // 1. Buat rapat baru
            $rapat = $this->RapatService->store([
                'jenis_rapat'     => 'RTM Pengendalian',
                'judul_kegiatan'  => 'RTM Pengendalian Periode ' . $periode->periode,
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
                'keterangan' => 'RTM Pengendalian Periode ' . $periode->periode,
            ]);

            // 3. Insert default agendas
            foreach (self::DEFAULT_AGENDAS as $i => $judul) {
                $this->RapatService->addAgenda($rapat, [
                    'judul_agenda' => $judul,
                    'isi'          => '',
                    'seq'          => $i + 1,
                ]);
            }

            logActivity('pemutu', "Membuat RTM Pengendalian untuk Periode {$periode->periode}");

            return $rapat;
        });
    }

    /**
     * Update data umum RTM (waktu, tempat, pejabat).
     */
    public function updateRtm(Rapat $rapat, array $data): Rapat
    {
        return $this->RapatService->update($rapat, $data);
    }
}
