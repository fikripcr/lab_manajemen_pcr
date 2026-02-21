<?php
namespace App\Services\Event;

use App\Models\Event\Rapat;
use App\Models\Event\RapatAgenda;
use App\Models\Event\RapatEntitas;
use App\Models\Event\RapatPeserta;
use Illuminate\Support\Facades\DB;

class RapatService
{
    public function store(array $data): Rapat
    {
        return DB::transaction(function () use ($data) {
            $rapat = Rapat::create($data);
            logActivity('event', "Menambah rapat baru: {$rapat->judul_kegiatan}");
            return $rapat;
        });
    }

    public function update(Rapat $rapat, array $data): Rapat
    {
        return DB::transaction(function () use ($rapat, $data) {
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

    public function updateAgendas(Rapat $rapat, array $agendasData): void
    {
        DB::transaction(function () use ($rapat, $agendasData) {
            foreach ($agendasData as $agendaId => $data) {
                $agenda = $rapat->agendas()->where('rapatagenda_id', $agendaId)->first();
                if ($agenda) {
                    $agenda->update(['isi' => $data['isi'] ?? '']);
                }
            }
        });
    }
}
