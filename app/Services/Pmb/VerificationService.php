<?php
namespace App\Services\Pmb;

use App\Models\Pmb\Pembayaran;
use App\Models\Pmb\Pendaftaran;
use Illuminate\Support\Facades\DB;

class VerificationService
{
    public function __construct(protected PendaftaranService $PendaftaranService)
    {}

    public function verifyPayment(Pembayaran $pembayaran, array $data): Pembayaran
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
                $this->PendaftaranService->updateStatus($pembayaran->pendaftaran, 'Menunggu_Verifikasi_Berkas', 'Pembayaran terverifikasi.');
            } else {
                $this->PendaftaranService->updateStatus($pembayaran->pendaftaran, 'Draft', 'Pembayaran ditolak: ' . $keterangan);
            }

            logActivity('pmb_verifikasi', "Verifikasi pembayaran untuk pendaftaran {$pembayaran->pendaftaran->no_pendaftaran}: {$status}", $pembayaran);

            return $pembayaran;
        });
    }

    public function verifyDocument(Pendaftaran $pendaftaran, array $data): Pendaftaran
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

                $this->PendaftaranService->updateStatus($pendaftaran, $nextStatus, 'Berkas terverifikasi.');
            } else {
                $this->PendaftaranService->updateStatus($pendaftaran, 'Draft', 'Berkas ditolak: ' . $keterangan);
            }

            logActivity('pmb_verifikasi', "Verifikasi berkas untuk pendaftaran {$pendaftaran->no_pendaftaran}: {$status}", $pendaftaran);

            return $pendaftaran;
        });
    }
}
