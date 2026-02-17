<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\Rapat;
use App\Models\Pemutu\RapatAgenda;
use App\Models\Pemutu\RapatEntitas;
use App\Models\Pemutu\RapatPeserta;
use Illuminate\Support\Facades\DB;

class RapatService
{
    public function store(array $data): Rapat
    {
        return DB::transaction(function () use ($data) {
            $rapat = Rapat::create($data);
            return $rapat;
        });
    }

    public function update(Rapat $rapat, array $data): Rapat
    {
        return DB::transaction(function () use ($rapat, $data) {
            $rapat->update($data);
            return $rapat;
        });
    }

    public function destroy(Rapat $rapat): void
    {
        DB::transaction(function () use ($rapat) {
            $rapat->delete();
        });
    }

    public function addAgenda(Rapat $rapat, array $data): RapatAgenda
    {
        return $rapat->agendas()->create($data);
    }

    public function addPeserta(Rapat $rapat, array $data): RapatPeserta
    {
        return $rapat->pesertas()->create($data);
    }

    public function addEntitas(Rapat $rapat, array $data): RapatEntitas
    {
        return $rapat->entitas()->create($data);
    }

    /**
     * Update participant attendance.
     */
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
                            $updateData['waktu_hadir'] = \Carbon\Carbon::parse("$date $time");
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
     * Update multiple agenda items.
     */
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
