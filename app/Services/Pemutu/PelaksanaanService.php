<?php
namespace App\Services\Pemutu;

use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\User;
use App\Services\Event\RapatService;
use Illuminate\Support\Facades\DB;

class PelaksanaanService
{
    public function __construct(
        protected RapatService $RapatService,
    ) {}

    /**
     * Get query for Pemantauan meetings.
     */
    public function getPemantauanQuery()
    {
        return Rapat::where('jenis_rapat', 'Pemantauan')
            ->with(['ketua_user', 'notulen_user', 'pesertas'])
            ->latest('tgl_rapat');
    }

    /**
     * Create a new Pemantauan meeting with indicator mapping.
     */
    public function createPemantauan(array $data): Rapat
    {
        return DB::transaction(function () use ($data) {
            // 1. Create the meeting
            $rapat = $this->RapatService->store([
                'jenis_rapat'     => 'Pemantauan',
                'judul_kegiatan'  => $data['judul_kegiatan'],
                'tgl_rapat'       => $data['tgl_rapat'],
                'waktu_mulai'     => $data['waktu_mulai'],
                'waktu_selesai'   => $data['waktu_selesai'],
                'tempat_rapat'    => $data['tempat_rapat'],
                'ketua_user_id'   => isset($data['ketua_user_id']) ? decryptIdIfEncrypted($data['ketua_user_id']) : null,
                'notulen_user_id' => isset($data['notulen_user_id']) ? decryptIdIfEncrypted($data['notulen_user_id']) : null,
                'author_user_id'  => auth()->id(),
                'keterangan'      => $data['keterangan'] ?? null,
            ]);

            // 2. Map Indikators via RapatEntitas
            if (! empty($data['indikorgunit_ids'])) {
                foreach ($data['indikorgunit_ids'] as $id) {
                    RapatEntitas::create([
                        'rapat_id'   => $rapat->rapat_id,
                        'model'      => 'IndikatorOrgUnit',
                        'model_id'   => decryptIdIfEncrypted($id),
                        'keterangan' => 'Pemantauan Indikator',
                    ]);
                }
            }

            logActivity('pemutu', "Membuat Rapat Pemantauan: {$rapat->judul_kegiatan}");

            return $rapat;
        });
    }

    /**
     * Update an existing Pemantauan meeting and its indicator mapping.
     */
    public function updatePemantauan(Rapat $rapat, array $data): Rapat
    {
        return DB::transaction(function () use ($rapat, $data) {
            // 1. Update general meeting info
            $this->RapatService->update($rapat, [
                'judul_kegiatan'  => $data['judul_kegiatan'],
                'tgl_rapat'       => $data['tgl_rapat'],
                'waktu_mulai'     => $data['waktu_mulai'],
                'waktu_selesai'   => $data['waktu_selesai'],
                'tempat_rapat'    => $data['tempat_rapat'],
                'ketua_user_id'   => isset($data['ketua_user_id']) ? decryptIdIfEncrypted($data['ketua_user_id']) : null,
                'notulen_user_id' => isset($data['notulen_user_id']) ? decryptIdIfEncrypted($data['notulen_user_id']) : null,
                'keterangan'      => $data['keterangan'] ?? null,
            ]);

            // 2. Sync Indikators (delete old ones first)
            if (isset($data['indikorgunit_ids'])) {
                RapatEntitas::where('rapat_id', $rapat->rapat_id)
                    ->where('model', 'IndikatorOrgUnit')
                    ->delete();

                foreach ($data['indikorgunit_ids'] as $id) {
                    RapatEntitas::create([
                        'rapat_id'   => $rapat->rapat_id,
                        'model'      => 'IndikatorOrgUnit',
                        'model_id'   => decryptIdIfEncrypted($id),
                        'keterangan' => 'Pemantauan Indikator',
                    ]);
                }
            }

            logActivity('pemutu', "Memperbarui Rapat Pemantauan: {$rapat->judul_kegiatan}");

            return $rapat;
        });
    }

    /**
     * Get monitoring meetings for a specific indicator.
     */
    public function getMonitoringForIndikator(IndikatorOrgUnit $indOrg)
    {
        return Rapat::where('jenis_rapat', 'Pemantauan')
            ->whereHas('entitas', function ($q) use ($indOrg) {
                $q->where('model', 'IndikatorOrgUnit')
                    ->where('model_id', $indOrg->indikorgunit_id);
            })
            ->with(['ketua_user', 'notulen_user'])
            ->latest('tgl_rapat')
            ->get();
    }
    /**
     * Get users with pegawai info for select dropdowns.
     */
    public function getUsersForSelect()
    {
        return User::with('pegawai.latestDataDiri')->get();
    }
}
