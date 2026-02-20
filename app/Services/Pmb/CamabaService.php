<?php
namespace App\Services\Pmb;

use App\Models\Pmb\DokumenUpload;
use App\Models\Pmb\Pembayaran;
use App\Models\Pmb\Pendaftaran;
use Illuminate\Support\Facades\DB;

class CamabaService
{
    protected $PendaftaranService;

    public function __construct(PendaftaranService $PendaftaranService)
    {
        $this->PendaftaranService = $PendaftaranService;
    }

    public function createRegistration(array $data)
    {
        return $this->PendaftaranService->createPendaftaran($data);
    }

    public function confirmPayment(Pendaftaran $pendaftaran, array $data, $file)
    {
        return DB::transaction(function () use ($pendaftaran, $data, $file) {
            $path = $file->store('pmb/pembayaran', 'public');

            $pembayaran = Pembayaran::create([
                'pendaftaran_id'    => $pendaftaran->id,
                'jenis_bayar'       => 'Formulir',
                'jumlah_bayar'      => $pendaftaran->jalur->biaya_pendaftaran,
                'bukti_bayar_path'  => $path,
                'bank_asal'         => $data['bank_asal'],
                'status_verifikasi' => 'Pending',
                'waktu_bayar'       => now(),
            ]);

            $this->PendaftaranService->updateStatus($pendaftaran->id, 'Menunggu_Verifikasi_Bayar', 'Camaba telah mengunggah bukti pembayaran.');

            return $pembayaran;
        });
    }

    public function uploadFile(Pendaftaran $pendaftaran, $jenisId, $file)
    {
        $path = $file->store('pmb/berkas', 'public');

        return DokumenUpload::updateOrCreate(
            ['pendaftaran_id' => $pendaftaran->id, 'jenis_dokumen_id' => $jenisId],
            [
                'path_file'         => $path,
                'status_verifikasi' => 'Pending',
                'waktu_upload'      => now(),
            ]
        );
    }

    public function finalizeFiles(Pendaftaran $pendaftaran)
    {
        return $this->PendaftaranService->updateStatus($pendaftaran->id, 'Menunggu_Verifikasi_Berkas', 'Camaba telah menyelesaikan unggah berkas.');
    }
}
