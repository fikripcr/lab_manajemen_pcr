<?php
namespace App\Services\Pmb;

use App\Models\Cbt\JadwalUjian;
use App\Models\Pmb\DokumenUpload;
use App\Models\Pmb\Jalur;
use App\Models\Pmb\Pembayaran;
use App\Models\Pmb\Pendaftaran;
use App\Models\Pmb\SyaratDokumenJalur;
use App\Models\Shared\StrukturOrganisasi;
use Illuminate\Support\Facades\DB;

class CamabaService
{

    public function getDashboardData($user)
    {
        $pendaftaran = Pendaftaran::with(['periode', 'jalur', 'pilihanProdi.orgUnit', 'orgUnitDiterima'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $periodeAktif = $this->PeriodeService->getActivePeriode();

        $activeJadwal = null;
        if ($pendaftaran && in_array($pendaftaran->status_terkini, ['Siap_Ujian', 'Sedang_Ujian'])) {
            $activeJadwal = JadwalUjian::whereIn('id', function ($query) use ($user) {
                $query->select('jadwal_id')->from('cbt_peserta_berhak')->where('user_id', $user->id);
            })
                ->where('waktu_mulai', '<=', now())
                ->where('waktu_selesai', '>=', now())
                ->first();
        }

        return compact('pendaftaran', 'periodeAktif', 'activeJadwal');
    }

    public function getRegistrationFormData($user)
    {
        $periodeAktif = $this->PeriodeService->getActivePeriode();
        if (! $periodeAktif) {
            return ['error' => 'Tidak ada periode pendaftaran yang aktif saat ini.'];
        }

        $existing = Pendaftaran::where('user_id', $user->id)
            ->where('periode_id', $periodeAktif->periode_id)
            ->first();

        if ($existing) {
            return ['info' => 'Anda sudah memiliki pendaftaran di periode ini.'];
        }

        $jalur  = Jalur::where('is_aktif', true)->get();
        $prodi  = StrukturOrganisasi::where('type', 'Prodi')->orderBy('name')->get();
        $profil = $user->profilPmb;

        return compact('periodeAktif', 'jalur', 'prodi', 'profil');
    }

    public function __construct(
        protected PendaftaranService $pendaftaranService,
        protected PeriodeService $PeriodeService
    ) {}

    public function createRegistration(array $data)
    {
        return $this->pendaftaranService->createPendaftaran($data);
    }

    public function confirmPayment(Pendaftaran $pendaftaran, array $data, $file)
    {
        return DB::transaction(function () use ($pendaftaran, $data, $file) {
            $path = $file->store('pmb/pembayaran', 'public');

            $pembayaran = Pembayaran::create([
                'pendaftaran_id'    => $pendaftaran->pendaftaran_id,
                'jenis_bayar'       => 'Formulir',
                'jumlah_bayar'      => $pendaftaran->jalur->biaya_pendaftaran,
                'bukti_bayar_path'  => $path,
                'bank_asal'         => $data['bank_asal'],
                'status_verifikasi' => 'Pending',
                'waktu_bayar'       => now(),
            ]);
            $this->pendaftaranService->updateStatus($pendaftaran, 'Menunggu_Verifikasi_Bayar', 'Camaba telah mengunggah bukti pembayaran.');

            logActivity('pmb_pembayaran', "Camaba mengunggah bukti pembayaran untuk pendaftaran {$pendaftaran->no_pendaftaran}", $pembayaran);

            return $pembayaran;
        });
    }

    public function uploadFile(Pendaftaran $pendaftaran, $jenisId, $file)
    {
        return DB::transaction(function () use ($pendaftaran, $jenisId, $file) {
            $path = $file->store('pmb/berkas', 'public');

            $upload = DokumenUpload::updateOrCreate(
                ['pendaftaran_id' => $pendaftaran->pendaftaran_id, 'jenis_dokumen_id' => $jenisId],
                [
                    'path_file'         => $path,
                    'status_verifikasi' => 'Pending',
                    'waktu_upload'      => now(),
                ]
            );

            logActivity('pmb_berkas', "Camaba mengunggah berkas untuk pendaftaran {$pendaftaran->no_pendaftaran}", $upload);

            return $upload;
        });
    }

    public function finalizeFiles(Pendaftaran $pendaftaran)
    {
        return $this->pendaftaranService->updateStatus($pendaftaran, 'Menunggu_Verifikasi_Berkas', 'Camaba telah menyelesaikan unggah berkas.');
    }

    public function getPendingPaymentRegistration($user)
    {
        return Pendaftaran::with('jalur')
            ->where('user_id', $user->id)
            ->where('status_terkini', 'Draft')
            ->latest()
            ->firstOrFail();
    }

    public function getUploadData($user)
    {
        $pendaftaran = Pendaftaran::with(['jalur', 'dokumenUpload.jenisDokumen'])
            ->where('user_id', $user->id)
            ->whereIn('status_terkini', ['Menunggu_Verifikasi_Berkas', 'Draft_Berkas', 'Revisi_Berkas'])
            ->latest()
            ->firstOrFail();

        $syarat = SyaratDokumenJalur::with('jenisDokumen')
            ->where('jalur_id', $pendaftaran->jalur_id)
            ->get();

        return compact('pendaftaran', 'syarat');
    }

    public function getExamCardData($user)
    {
        return Pendaftaran::with(['periode', 'jalur', 'pilihanProdi.orgUnit', 'pesertaUjian.sesiUjian'])
            ->where('user_id', $user->id)
            ->latest()
            ->firstOrFail();
    }
}
