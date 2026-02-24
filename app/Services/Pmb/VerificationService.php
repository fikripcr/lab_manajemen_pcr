<?php
namespace App\Services\Pmb;

use App\Models\Pmb\Pembayaran;
use App\Models\Pmb\Pendaftaran;
use App\Models\Shared\RiwayatApproval;
use Illuminate\Support\Facades\DB;

class VerificationService
{
    public function __construct(protected PendaftaranService $pendaftaranService)
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

            $stdStatus = ($status == 'Verified' ? 'Approved' : 'Rejected');

            // Record Standardized Approval
            RiwayatApproval::create([
                'model'    => Pendaftaran::class,
                'model_id' => $pembayaran->pendaftaran_id,
                'status'   => $stdStatus,
                'pejabat'  => auth()->user()->name,
                'catatan'  => 'Verifikasi Pembayaran: ' . $keterangan,
            ]);

            if ($status == 'Verified') {
                $this->pendaftaranService->updateStatus($pembayaran->pendaftaran, 'Menunggu_Verifikasi_Berkas', 'Pembayaran terverifikasi.');
            } else {
                $this->pendaftaranService->updateStatus($pembayaran->pendaftaran, 'Draft', 'Pembayaran ditolak: ' . $keterangan);
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

            $stdStatus = ($status == 'Verified' ? 'Approved' : 'Rejected');

            // Record Standardized Approval
            RiwayatApproval::create([
                'model'    => Pendaftaran::class,
                'model_id' => $pendaftaran->pendaftaran_id,
                'status'   => $stdStatus,
                'pejabat'  => auth()->user()->name,
                'catatan'  => 'Verifikasi Berkas: ' . $keterangan,
            ]);

            if ($status == 'Verified') {
                $nextStatus = 'Menunggu_Jadwal_Ujian';
                if ($pendaftaran->jalur->needs_exam ?? true) {
                    $nextStatus = 'Menunggu_Jadwal_Ujian';
                } else {
                    $nextStatus = 'Lulus_Administrasi';
                }

                $this->pendaftaranService->updateStatus($pendaftaran, $nextStatus, 'Berkas terverifikasi.');
            } else {
                $this->pendaftaranService->updateStatus($pendaftaran, 'Draft', 'Berkas ditolak: ' . $keterangan);
            }

            logActivity('pmb_verifikasi', "Verifikasi berkas untuk pendaftaran {$pendaftaran->no_pendaftaran}: {$status}", $pendaftaran);

            return $pendaftaran;
        });
    }

    public function updatePendaftaranStatus(Pendaftaran $pendaftaran, string $status, ?string $keterangan): Pendaftaran
    {
        return DB::transaction(function () use ($pendaftaran, $status, $keterangan) {
            $this->pendaftaranService->updateStatus($pendaftaran, $status, $keterangan);

            $stdStatus = 'Approved';
            if (str_contains(strtolower($status), 'tolak') || str_contains(strtolower($status), 'gagal')) {
                $stdStatus = 'Rejected';
            }

            RiwayatApproval::create([
                'model'    => Pendaftaran::class,
                'model_id' => $pendaftaran->pendaftaran_id,
                'status'   => $stdStatus,
                'pejabat'  => auth()->user()?->name ?? 'System',
                'catatan'  => "Update Status Ke {$status}: " . $keterangan,
            ]);

            return $pendaftaran;
        });
    }
}
