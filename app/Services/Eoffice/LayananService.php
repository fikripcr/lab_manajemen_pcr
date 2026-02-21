<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\Layanan;
use App\Models\Eoffice\Mahasiswa;
use App\Models\Eoffice\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LayananService
{
    /**
     * Get paginated data for DataTables
     */
    public function getFilteredQuery(Request $request, $scope = 'all')
    {
        $query = Layanan::with(['jenisLayanan', 'latestStatus']);

        if ($scope === 'user') {
            $query->where('created_by', Auth::id());
        } elseif ($scope === 'pic') {
            // Filter by JenisLayanan that the user is PIC of
            $query->whereHas('jenisLayanan.pics', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        if ($request->filled('search')) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('no_layanan', 'like', "%{$search}%")
                    ->orWhere('pengusul_nama', 'like', "%{$search}%")
                    ->orWhereHas('jenisLayanan', function ($q2) use ($search) {
                        $q2->where('nama_layanan', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('latestStatus', function ($q) use ($request) {
                $q->where('status_layanan', $request->status);
            });
        }

        if ($request->filled('jenislayanan_id')) {
            $query->where('jenislayanan_id', $request->jenislayanan_id);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [
                    trim($dates[0]) . ' 00:00:00',
                    trim($dates[1]) . ' 23:59:59',
                ]);
            }
        }

        return $query;
    }

    /**
     * Submit a new layout request
     */
    public function createLayanan(array $data, array $dynamicFields)
    {
        return DB::transaction(function () use ($data, $dynamicFields) {
            $jenisLayanan = JenisLayanan::findOrFail($data['jenislayanan_id']);

            // 1. Create Main Record
            $layanan = new Layanan();
            $layanan->fill($data);
            $layanan->no_layanan = $this->generateNoLayanan($jenisLayanan);

            // Auto-fill applicant info from session/profile if not provided
            $this->autoFillApplicant($layanan);

            $layanan->save();

            // 2. Initial Status
            $status = $layanan->statuses()->create([
                'status_layanan' => 'Diajukan',
                'keterangan'     => 'Permohonan berhasil dikirim.',
            ]);

            $layanan->update(['latest_layananstatus_id' => $status->layananstatus_id]);

            // 3. Store Dynamic Fields
            foreach ($dynamicFields as $nama_isian => $isi) {
                $layanan->isians()->create([
                    'nama_isian' => $nama_isian,
                    'isi'        => is_array($isi) ? json_encode($isi) : $isi,
                ]);
            }

            logActivity('eoffice', "Mengajukan layanan baru: {$layanan->no_layanan} ({$jenisLayanan->nama_layanan})");

            return $layanan;
        });
    }

    /**
     * Update status (Disposition)
     */
    public function updateStatus($layananId, array $data)
    {
        return DB::transaction(function () use ($layananId, $data) {
            $layanan = Layanan::findOrFail($layananId);

            $status = $layanan->statuses()->create([
                'status_layanan' => $data['status_layanan'],
                'keterangan'     => $data['keterangan'] ?? null,
                'file_lampiran'  => $data['file_lampiran'] ?? null,
                'user_id'        => Auth::id(), // Ensure user_id is set
            ]);

            $layanan->update(['latest_layananstatus_id' => $status->layananstatus_id]);

            logActivity('eoffice', "Memperbarui status layanan {$layanan->no_layanan} menjadi: {$data['status_layanan']}");

            return $status;
        });
    }

    /**
     * Generate dynamic reference number
     */
    private function generateNoLayanan(JenisLayanan $jl)
    {
        $prefix = strtoupper(substr($jl->kategori, 0, 3));
        $date   = date('Ymd');
        $count  = Layanan::where('no_layanan', 'like', "{$prefix}-{$date}-%")->count() + 1;

        return sprintf("%s-%s-%04d", $prefix, $date, $count);
    }

    /**
     * Auto fill applicant data
     */
    private function autoFillApplicant(Layanan $layanan)
    {
        $user = Auth::user();

        // Try to get from Pegawai or Mahasiswa tables
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        if ($pegawai) {
            $layanan->pengusul_nama    = $pegawai->nama;
            $layanan->pengusul_nim     = $pegawai->nip;
            $layanan->pengusul_prodi   = $pegawai->departemen;
            $layanan->pengusul_inisial = $pegawai->inisial;
            return;
        }

        $mhs = Mahasiswa::where('user_id', $user->id)->first();
        if ($mhs) {
            $layanan->pengusul_nama  = $mhs->nama;
            $layanan->pengusul_nim   = $mhs->nim;
            $layanan->pengusul_prodi = $mhs->prodi->nama_prodi ?? null;
            return;
        }

        // Fallback to User model
        $layanan->pengusul_nama = $layanan->pengusul_nama ?? $user->name;
    }
}
