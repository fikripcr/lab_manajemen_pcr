<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\Layanan;
use App\Models\Eoffice\LayananIsian;
use App\Models\Eoffice\LayananStatus;
use App\Models\Eoffice\TanggalTidakHadir;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LayananStatusService
{
    /**
     * Update the status of a layanan (disposition workflow).
     *
     * @param int    $layananId  The layanan ID (decrypted)
     * @param string $status     Short status action: 'proses', 'batal', or null for form-based
     * @param array  $data       Request data containing status_layanan, keterangan, etc.
     * @return LayananStatus
     */
    public function update($layananId, $status, array $data = [])
    {
        return DB::transaction(function () use ($layananId, $status, $data) {
            $layanan = Layanan::with(['jenisLayanan', 'latestStatus'])->findOrFail($layananId);
            $user    = Auth::user();

            $currentStatus = $layanan->latestStatus;

            // ── Mark previous status as done ──
            if ($currentStatus) {
                $currentStatus->done_at       = now();
                $currentStatus->done_by_email = $user->email;

                if ($layanan->jenisLayanan && $layanan->jenisLayanan->batas_pengerjaan > 0) {
                    $created                      = $currentStatus->created_at;
                    $currentStatus->done_duration = $created->diffForHumans(now(), true);
                }
                $currentStatus->save();
            }

            // ── Determine new status ──
            if ($status === 'proses') {
                $newStatus = 'Diproses';
            } elseif ($status === 'batal') {
                $newStatus = 'Dibatalkan';
            } else {
                $newStatus = $data['status_layanan'] ?? 'Diproses';
            }

            // ── Build keterangan ──
            $keterangan = $data['keterangan'] ?? ('Layanan ' . $newStatus . ' oleh ' . $user->name);

            $disposisiInfo = null;

            // ── Handle Disposisi Chain ──
            if ($newStatus === 'Disposisi') {
                $currentDisposisiInfo = $layanan->disposisi_info;
                $currentSeq           = $currentDisposisiInfo['seq'] ?? null;

                // Validate required isian fields for current disposisi step
                $this->validateIsianForDisposisi($layananId, $currentSeq);

                // Create "Diterima" status for current step
                LayananStatus::create([
                    'layanan_id'     => $layananId,
                    'status_layanan' => 'Diterima',
                    'keterangan'     => 'Layanan Disetujui oleh ' . $user->name,
                    'disposisi_info' => $currentDisposisiInfo,
                ]);

                // Find next disposisi
                $nextSeq       = $data['disposisi_seq'] ?? (($currentSeq ?? 0) + 1);
                $disposisiList = $layanan->disposisi_list ?? [];

                foreach ($disposisiList as $dl) {
                    $dlObj = is_array($dl) ? (object) $dl : $dl;
                    if (($dlObj->seq ?? null) == $nextSeq) {
                        $disposisiInfo = (array) $dlObj;
                        $keterangan    = 'Layanan Diteruskan ke ' . ($dlObj->value ?? 'Selanjutnya');
                        break;
                    }
                }
            }

            // ── Handle Selesai (Completion) — create TanggalTidakHadir if applicable ──
            if ($newStatus === 'Selesai') {
                $this->handleCompletionAbsences($layananId, $layanan->jenisLayanan->nama_layanan ?? '');
            }

            // ── Create new LayananStatus ──
            $layananStatus = LayananStatus::create([
                'layanan_id'     => $layananId,
                'status_layanan' => $newStatus,
                'keterangan'     => $keterangan,
                'file_lampiran'  => $data['file_lampiran'] ?? null,
                'disposisi_info' => $disposisiInfo,
            ]);

            // ── Update Layanan's latest status + PIC ──
            $updateData = ['latest_layananstatus_id' => $layananStatus->layananstatus_id];

            if ($newStatus === 'Diproses' && $currentStatus && $currentStatus->status_layanan === 'Diajukan') {
                $updateData['pic_awal'] = $user->id;
            }

            if ($disposisiInfo) {
                $updateData['disposisi_info'] = $disposisiInfo;
            }

            $layanan->update($updateData);

            logActivity('eoffice_layanan', "Memperbarui status layanan {$layanan->no_layanan} menjadi '{$newStatus}'" . ($disposisiInfo ? " (Ke {$disposisiInfo['value']})" : ""));

            // TODO: Send email notifications when notification system is available.

            return $layananStatus;
        });
    }

    /**
     * Validate that required isian fields for the current disposisi step are filled.
     */
    private function validateIsianForDisposisi($layananId, $currentSeq)
    {
        if (! $currentSeq) {
            return;
        }

        $isianItems = LayananIsian::where('layanan_id', $layananId)->get();

        foreach ($isianItems as $item) {
            // Check if this isian is assigned to the current disposisi step
            // and is not filled yet — pattern from original e-office
            if (isset($item->fill_by) && $item->fill_by === 'Disposisi ' . $currentSeq && empty($item->isi)) {
                throw new Exception('Anda perlu mengisi Data Isian: "' . $item->nama_isian . '"');
            }
        }
    }

    /**
     * Create TanggalTidakHadir records when a service is completed
     * (for services that affect attendance: cuti, izin, etc.)
     */
    private function handleCompletionAbsences($layananId, $namaLayanan)
    {
        $isianItems = LayananIsian::where('layanan_id', $layananId)->get();

        $listTanggal    = null;
        $additionalInfo = [];

        foreach ($isianItems as $item) {
            $alias = $item->alias_on_document ?? $item->nama_isian;

            if (in_array($alias, ['tanggal_bertugas', 'tanggal_cuti_tahunan', 'tanggal_cuti_bersalin', 'tanggal_izin'])) {
                $listTanggal = $item->isi;
            }

            $additionalInfo[$alias] = $item->isi;
        }

        if (! $listTanggal) {
            return;
        }

        $dates = explode(',', $listTanggal);
        foreach ($dates as $tgl) {
            $tgl = trim($tgl);
            if (empty($tgl)) {
                continue;
            }

            $dataSet = [
                'jenis_ketidakhadiran' => $namaLayanan,
                'tgl'                  => $tgl,
                'model'                => 'Layanan',
                'model_id'             => $layananId,
                'additional_info'      => $additionalInfo,
                'is_full_day'          => true,
            ];

            if (! empty($additionalInfo['waktu_mulai']) && ! empty($additionalInfo['waktu_selesai'])) {
                $dataSet['is_full_day']   = false;
                $dataSet['waktu_mulai']   = $additionalInfo['waktu_mulai'];
                $dataSet['waktu_selesai'] = $additionalInfo['waktu_selesai'];
            }

            TanggalTidakHadir::create($dataSet);
        }
    }
}
