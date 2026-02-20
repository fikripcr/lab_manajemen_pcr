<?php
namespace App\Services\Pmb;

use App\Models\Pmb\Pembayaran;
use App\Models\Pmb\Pendaftaran;
use Illuminate\Support\Facades\DB;

class VerificationService
{
    protected $PendaftaranService;

    public function __construct(PendaftaranService $PendaftaranService)
    {
        $this->PendaftaranService = $PendaftaranService;
    }

    public function verifyPayment(Pembayaran $pembayaran, array $data)
    {
        return DB::transaction(function () use ($pembayaran, $data) {
            $status     = $data['status'];
            $keterangan = $data['keterangan'] ?? null;

            $pembayaran->update([
                'status_verifikasi' => ($status == 'Verified' ? 'Lunas' : 'Ditolak'),
                'verifikator_id'    => auth()->id(),
                'waktu_bayar'       => now(),
            ]);

            if ($status == 'Verified') {
                $this->PendaftaranService->updateStatus($pembayaran->pendaftaran_id, 'Menunggu_Verifikasi_Berkas', 'Pembayaran terverifikasi.');
            } else {
                $this->PendaftaranService->updateStatus($pembayaran->pendaftaran_id, 'Draft', 'Pembayaran ditolak: ' . $keterangan);
            }

            return $pembayaran;
        });
    }

    public function verifyDocument(Pendaftaran $pendaftaran, array $data)
    {
        return DB::transaction(function () use ($pendaftaran, $data) {
            $status     = $data['status'];
            $keterangan = $data['keterangan'] ?? null;

            if ($status == 'Verified') {
                $nextStatus = 'Menunggu_Jadwal_Ujian';
                // Logic to check if this jalur needs exam or direct pass
                if ($pendaftaran->jalur->needs_exam ?? true) {
                    $nextStatus = 'Menunggu_Jadwal_Ujian';
                } else {
                    $nextStatus = 'Lulus_Administrasi';
                }

                $this->PendaftaranService->updateStatus($pendaftaran->id, $nextStatus, 'Berkas terverifikasi.');
            } else {
                $this->PendaftaranService->updateStatus($pendaftaran->id, 'Draft', 'Berkas ditolak: ' . $keterangan);
            }

            return $pendaftaran;
        });
    }
}
