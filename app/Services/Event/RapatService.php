<?php
namespace App\Services\Event;

use App\Models\Event\Rapat;
use App\Models\Event\RapatAgenda;
use App\Models\Event\RapatEntitas;
use App\Models\Event\RapatPeserta;
use App\Models\User;
use App\Notifications\Event\RapatUndanganNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RapatService
{
    public function store(array $data): Rapat
    {
        return DB::transaction(function () use ($data) {
            $date                  = $data['tgl_rapat'];
            $data['waktu_mulai']   = \Carbon\Carbon::parse("$date " . $data['waktu_mulai']);
            $data['waktu_selesai'] = \Carbon\Carbon::parse("$date " . $data['waktu_selesai']);
            $rapat                 = Rapat::create($data);
            logActivity('event', "Menambah rapat baru: {$rapat->judul_kegiatan}");
            return $rapat;
        });
    }

    public function update(Rapat $rapat, array $data): Rapat
    {
        return DB::transaction(function () use ($rapat, $data) {
            $date                  = $data['tgl_rapat'];
            $data['waktu_mulai']   = \Carbon\Carbon::parse("$date " . $data['waktu_mulai']);
            $data['waktu_selesai'] = \Carbon\Carbon::parse("$date " . $data['waktu_selesai']);
            $rapat->update($data);
            logActivity('event', "Memperbarui rapat: {$rapat->judul_kegiatan}");
            return $rapat;
        });
    }

    public function destroy(Rapat $rapat): void
    {
        DB::transaction(function () use ($rapat) {
            $judul = $rapat->judul_kegiatan;
            $rapat->delete();
            logActivity('event', "Menghapus rapat: {$judul}");
        });
    }

    public function addAgenda(Rapat $rapat, array $data): RapatAgenda
    {
        $agenda = $rapat->agendas()->create($data);
        logActivity('event', "Menambah agenda rapat '{$agenda->judul_agenda}' pada rapat: {$rapat->judul_kegiatan}");
        return $agenda;
    }

    public function addPeserta(Rapat $rapat, array $data): RapatPeserta
    {
        $peserta = $rapat->pesertas()->create($data);
        logActivity('event', "Menambah peserta '" . ($peserta->user->name ?? 'User') . "' ke rapat: {$rapat->judul_kegiatan}");
        return $peserta;
    }

    /**
     * Tambahkan banyak peserta sekaligus, skip yang sudah terdaftar.
     * Mendukung peserta internal (user_ids) dan peserta luar (peserta_luar[]).
     */
    public function bulkAddPeserta(Rapat $rapat, array $data): void
    {
        DB::transaction(function () use ($rapat, $data) {
            // Internal users
            $userIds = $data['user_ids'] ?? [];
            $jabatan = $data['jabatan_internal'] ?? $data['jabatan'] ?? 'Peserta';
            foreach ($userIds as $userId) {
                if (! $rapat->pesertas()->where('user_id', $userId)->exists()) {
                    $this->addPeserta($rapat, [
                        'user_id' => $userId,
                        'jabatan' => $jabatan,
                    ]);
                }
            }

            // External participants (peserta luar)
            $pesertaLuar = $data['peserta_luar'] ?? [];
            foreach ($pesertaLuar as $luar) {
                $nama = trim($luar['nama'] ?? '');
                if (empty($nama)) {
                    continue;
                }

                $this->addPeserta($rapat, [
                    'user_id'    => null,
                    'nama_luar'  => $nama,
                    'email_luar' => $luar['email'] ?? null,
                    'jabatan'    => $luar['jabatan'] ?? 'Peserta Luar',
                ]);
            }
        });
    }

    public function addEntitas(Rapat $rapat, array $data): RapatEntitas
    {
        $entitas = $rapat->entitas()->create($data);
        logActivity('event', "Menambah entitas ke rapat: {$rapat->judul_kegiatan}");
        return $entitas;
    }

    public function updateAttendance(Rapat $rapat, array $attendanceData): void
    {
        DB::transaction(function () use ($rapat, $attendanceData) {
            foreach ($attendanceData as $pesertaId => $data) {
                $peserta = $rapat->pesertas()->where('rapatpeserta_id', $pesertaId)->first();
                if ($peserta) {
                    $updateData = ['status' => $data['status']];

                    if ($data['status'] == 'hadir') {
                        if (! empty($data['waktu_hadir'])) {
                            $time                      = $data['waktu_hadir'];
                            $date                      = $rapat->tgl_rapat->format('Y-m-d');
                            $dateTime                  = \Carbon\Carbon::parse("$date $time");
                            $updateData['waktu_hadir'] = $dateTime;
                        }
                    } else {
                        $updateData['waktu_hadir'] = null;
                    }

                    $peserta->update($updateData);
                }
            }
        });
    }

    /**
     * Toggle kehadiran satu peserta (hadir/tidak hadir) via switch.
     * Menyimpan waktu saat toggle dan siapa yang mengklik.
     */
    public function toggleAttendance(RapatPeserta $peserta): RapatPeserta
    {
        $isHadir = $peserta->status === 'hadir';
        $peserta->update([
            'status'      => $isHadir ? null : 'hadir',
            'waktu_hadir' => $isHadir ? null : now(),
            'updated_by'  => auth()->id(),
        ]);
        logActivity('event', ($isHadir ? 'Menandai tidak hadir' : 'Menandai hadir') . ': ' . ($peserta->user->name ?? '-'));
        return $peserta->fresh();
    }

    public function updateAgendas(Rapat $rapat, array $agendasData): void
    {
        DB::transaction(function () use ($rapat, $agendasData) {
            foreach ($agendasData as $agendaId => $data) {
                $agenda = $rapat->agendas()->where('rapatagenda_id', $agendaId)->first();
                if ($agenda) {
                    $oldIsi = $agenda->isi;
                    $agenda->update(['isi' => $data['isi'] ?? '']);

                    // Audit trail for notulen changes
                    if ($oldIsi !== $data['isi']) {
                        logActivity('event', "Mengubah notulen agenda '{$agenda->judul_agenda}' pada rapat: {$rapat->judul_kegiatan}");
                    }
                }
            }
        });
    }

    /**
     * Invite participants to rapat and send email notifications
     */
    public function inviteParticipants(Rapat $rapat, array $userIds, string $jabatan = 'Peserta'): array
    {
        $result = [
            'invited'        => [],
            'already_exists' => [],
            'failed'         => [],
        ];

        DB::transaction(function () use ($rapat, $userIds, $jabatan, &$result) {
            foreach ($userIds as $userId) {
                try {
                    // Check if participant already exists
                    $existingPeserta = $rapat->pesertas()->where('user_id', $userId)->first();

                    if ($existingPeserta) {
                        $result['already_exists'][] = $userId;
                        continue;
                    }

                    // Create new participant with invite status
                    $peserta = $this->addPeserta($rapat, [
                        'user_id'    => $userId,
                        'jabatan'    => $jabatan,
                        'status'     => null, // Belum absen
                        'is_invited' => true,
                    ]);

                    $result['invited'][] = $peserta;
                } catch (\Exception $e) {
                    $result['failed'][] = [
                        'user_id' => $userId,
                        'error'   => $e->getMessage(),
                    ];
                    Log::error('Failed to invite participant to rapat', [
                        'rapat_id' => $rapat->rapat_id,
                        'user_id'  => $userId,
                        'error'    => $e->getMessage(),
                    ]);
                }
            }
        });

        // Send email notifications after transaction commits
        foreach ($result['invited'] as $peserta) {
            try {
                $user = User::find($peserta->user_id);
                if ($user && $user->email) {
                    $user->notify(new RapatUndanganNotification($rapat, $jabatan));

                    $peserta->update([
                        'invitation_sent_at' => now(),
                    ]);

                    logActivity('event', "Mengirim undangan email ke {$user->name} untuk rapat: {$rapat->judul_kegiatan}");
                }
            } catch (\Exception $e) {
                Log::error('Failed to send invitation email', [
                    'rapat_id' => $rapat->rapat_id,
                    'user_id'  => $peserta->user_id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        return $result;
    }

    /**
     * Resend invitation email to participant
     */
    public function resendInvitation(RapatPeserta $peserta): bool
    {
        try {
            $user = User::find($peserta->user_id);
            if (! $user || ! $user->email) {
                return false;
            }

            $rapat = $peserta->rapat;
            $user->notify(new RapatUndanganNotification($rapat, $peserta->jabatan));

            $peserta->update([
                'invitation_sent_at' => now(),
            ]);

            logActivity('event', "Mengirim ulang undangan email ke {$user->name} untuk rapat: {$rapat->judul_kegiatan}");

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to resend invitation email', [
                'peserta_id' => $peserta->rapatpeserta_id,
                'error'      => $e->getMessage(),
            ]);
            return false;
        }
    }
}
