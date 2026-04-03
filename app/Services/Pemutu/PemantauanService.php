<?php

namespace App\Services\Pemutu;

use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use App\Services\Event\RapatService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PemantauanService
{
    public function __construct(
        protected RapatService $rapatService
    ) {}

    /**
     * Create a new Monitoring Meeting (Pemantauan).
     */
    public function savePemantauan(array $data): Rapat
    {
        return DB::transaction(function () use ($data) {
            $rapat = $this->rapatService->store([
                'jenis_rapat'     => 'Pemantauan',
                'judul_kegiatan' => $data['judul_kegiatan'],
                'tgl_rapat'      => $data['tgl_rapat'],
                'waktu_mulai'    => $data['waktu_mulai'],
                'waktu_selesai'  => $data['waktu_selesai'],
                'tempat_rapat'   => $data['tempat_rapat'],
                'ketua_user_id'  => isset($data['ketua_user_id']) ? decryptIdIfEncrypted($data['ketua_user_id']) : null,
                'notulen_user_id' => isset($data['notulen_user_id']) ? decryptIdIfEncrypted($data['notulen_user_id']) : null,
                'author_user_id' => auth()->id(),
                'keterangan'     => $data['keterangan'] ?? null,
            ]);

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

            if (! empty($data['agendas'])) {
                foreach ($data['agendas'] as $index => $agendaItem) {
                    if (! empty($agendaItem['judul_agenda'])) {
                        $this->rapatService->addAgenda($rapat, [
                            'judul_agenda' => $agendaItem['judul_agenda'],
                            'isi'          => '',
                            'seq'          => $index,
                        ]);
                    }
                }
            }

            if (! empty($data['participants'])) {
                $this->rapatService->inviteParticipants(
                    $rapat,
                    $data['participants'],
                    $data['jabatan_peserta'] ?? 'Peserta'
                );
            }

            return $rapat;
        });
    }

    /**
     * Update an existing Monitoring Meeting.
     */
    public function updatePemantauan(Rapat $rapat, array $data): Rapat
    {
        return DB::transaction(function () use ($rapat, $data) {
            $this->rapatService->update($rapat, [
                'judul_kegiatan' => $data['judul_kegiatan'],
                'tgl_rapat'      => $data['tgl_rapat'],
                'waktu_mulai'    => $data['waktu_mulai'],
                'waktu_selesai'  => $data['waktu_selesai'],
                'tempat_rapat'   => $data['tempat_rapat'],
                'ketua_user_id'  => isset($data['ketua_user_id']) ? decryptIdIfEncrypted($data['ketua_user_id']) : null,
                'notulen_user_id' => isset($data['notulen_user_id']) ? decryptIdIfEncrypted($data['notulen_user_id']) : null,
                'keterangan'     => $data['keterangan'] ?? null,
            ]);

            // Sync Indikators
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

            // Agendas
            if (! empty($data['agendas'])) {
                foreach ($data['agendas'] as $index => $agendaItem) {
                    if (! empty($agendaItem['judul_agenda']) && empty($agendaItem['rapatagenda_id'])) {
                        $this->rapatService->addAgenda($rapat, [
                            'judul_agenda' => $agendaItem['judul_agenda'],
                            'isi'          => '',
                            'seq'          => $index,
                        ]);
                    }
                }
            }

            // Participants
            if (! empty($data['participants'])) {
                $this->rapatService->inviteParticipants(
                    $rapat,
                    $data['participants'],
                    $data['jabatan_peserta'] ?? 'Peserta'
                );
            }

            return $rapat;
        });
    }

    /**
     * Query for Monitoring meetings.
     */
    public function getPemantauanQuery(): Builder
    {
        return Rapat::query()
            ->where('jenis_rapat', 'Pemantauan')
            ->with(['ketua_user.pegawai.latestDataDiri', 'notulen_user.pegawai.latestDataDiri', 'pesertas'])
            ->latest('tgl_rapat');
    }

    /**
     * Get monitoring history for a specific organization unit indicator.
     */
    public function getMonitoringHistory(string|int $id): Collection
    {
        $id = decryptIdIfEncrypted($id);
        return Rapat::where('jenis_rapat', 'Pemantauan')
            ->whereHas('entitas', function ($q) use ($id) {
                $q->where('model', 'IndikatorOrgUnit')
                    ->where('model_id', $id);
            })
            ->with(['ketua_user', 'notulen_user'])
            ->latest('tgl_rapat')
            ->get();
    }

}
