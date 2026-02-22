<?php
namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cbt\StoreJadwalRequest;
use App\Models\Cbt\JadwalUjian;
use App\Models\Cbt\PaketUjian;
use App\Services\Cbt\JadwalUjianService;
use Exception;
use Illuminate\Http\Request;

class JadwalUjianController extends Controller
{
    public function __construct(protected JadwalUjianService $jadwalUjianService)
    {}

    public function index()
    {
        return view('pages.cbt.jadwal.index');
    }

    public function paginate(Request $request)
    {
        $query = $this->jadwalUjianService->getFilteredQuery($request->all());
        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('kegiatan_paket', function($j) {
                return '<div class="d-flex flex-column gap-1">
                    <div class="fw-bold text-primary">' . e($j->nama_kegiatan) . '</div>
                    <div class="text-muted small"><i class="ti ti-package me-1"></i>' . e($j->paket->nama_paket ?? '-') . '</div>
                </div>';
            })
            ->addColumn('token_info', function($j) {
                $badgeClass = $j->is_token_aktif ? 'bg-success-lt text-success' : 'bg-secondary-lt text-secondary';
                $icon = $j->is_token_aktif ? 'ti-check' : 'ti-x';
                $statusText = $j->is_token_aktif ? 'Aktif' : 'Nonaktif';
                return '<div class="d-flex flex-column gap-2">
                    <div class="fw-bold font-monospace">' . e($j->token_ujian ?? '-') . '</div>
                    <span class="badge ' . $badgeClass . ' badge-sm w-fit">
                        <i class="' . $icon . ' me-1"></i>' . $statusText . '
                    </span>
                </div>';
            })
            ->addColumn('waktu_status', function($j) {
                $now = now();
                $isStarted = $now->gte($j->waktu_mulai);
                $isEnded = $now->gte($j->waktu_selesai);
                
                if ($isEnded) {
                    $statusBadge = '<span class="badge bg-secondary-lt text-secondary badge-sm"><i class="ti ti-check me-1"></i>Selesai</span>';
                } elseif ($isStarted) {
                    $statusBadge = '<span class="badge bg-success-lt text-success badge-sm"><i class="ti ti-player-play me-1"></i>Berlangsung</span>';
                } else {
                    $statusBadge = '<span class="badge bg-azure-lt text-azure badge-sm"><i class="ti ti-calendar me-1"></i>Akan Datang</span>';
                }
                
                return '<div class="d-flex flex-column gap-2">
                    <div class="small"><i class="ti ti-calendar-event me-1"></i>' . $j->waktu_mulai->format('d M Y') . '</div>
                    <div class="small"><i class="ti ti-clock me-1"></i>' . $j->waktu_mulai->format('H:i') . ' - ' . $j->waktu_selesai->format('H:i') . ' WIB</div>
                    <div>' . $statusBadge . '</div>
                </div>';
            })
            ->addColumn('peserta', function($j) {
                $jumlahPeserta = $j->pesertaBerhak->count();
                $jumlahPelanggaran = $j->riwayatSiswa->sum(function($r) {
                    return $r->pelanggaran->count();
                });
                
                return '<div class="d-flex flex-column gap-1">
                    <div class="fw-bold"><i class="ti ti-users me-1"></i>' . $jumlahPeserta . '</div>
                    ' . ($jumlahPelanggaran > 0 ? '<div class="text-danger small"><i class="ti ti-alert-triangle me-1"></i>' . $jumlahPelanggaran . ' pelanggaran</div>' : '') . '
                </div>';
            })
            ->editColumn('waktu_mulai', fn($j) => formatTanggalIndo($j->waktu_mulai))
            ->editColumn('waktu_selesai', fn($j) => formatTanggalIndo($j->waktu_selesai))
            ->addColumn('action', function ($j) {
                $customActions = [
                    [
                        'url'        => route('cbt.execute.monitor', $j->encrypted_jadwal_ujian_id),
                        'label'      => 'Monitoring Ujian',
                        'icon'       => 'chart-bar',
                        'class'      => '',
                        'attributes' => '',
                    ],
                    [
                        'url'        => 'javascript:void(0)',
                        'label'      => 'Generate Token',
                        'icon'       => 'rotate',
                        'class'      => 'btn-jadwal-action',
                        'attributes' => 'data-url="' . route('cbt.jadwal.generate-token', $j) . '"',
                    ],
                    [
                        'url'        => 'javascript:void(0)',
                        'label'      => $j->is_token_aktif ? 'Nonaktifkan' : 'Aktifkan',
                        'icon'       => $j->is_token_aktif ? 'eye-off' : 'eye',
                        'class'      => 'btn-jadwal-action',
                        'attributes' => 'data-url="' . route('cbt.jadwal.toggle-token', $j) . '"',
                    ],
                ];

                if (auth()->user()->hasRole('admin')) {
                    $customActions[] = [
                        'url'   => route('cbt.execute.test', $j->encrypted_jadwal_ujian_id),
                        'label' => 'Test Ujian',
                        'icon'  => 'player-play',
                        'class' => '',
                    ];
                }

                return view('components.tabler.datatables-actions', [
                    'editUrl'       => route('cbt.jadwal.edit', $j->encrypted_jadwal_ujian_id),
                    'editModal'     => true,
                    'editTitle'     => 'Edit Jadwal Ujian',
                    'deleteUrl'     => route('cbt.jadwal.destroy', $j->encrypted_jadwal_ujian_id),
                    'customActions' => $customActions,
                ])->render();
            })
            ->rawColumns(['kegiatan_paket', 'token_info', 'waktu_status', 'peserta', 'action'])
            ->make(true);
    }

    public function create()
    {
        $paket = PaketUjian::all();
        return view('pages.cbt.jadwal.create-edit-ajax', compact('paket'));
    }

    public function store(StoreJadwalRequest $request)
    {
        try {
            $this->jadwalUjianService->store($request->validated());
            return jsonSuccess('Jadwal ujian berhasil dibuat.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal membuat jadwal ujian.');
        }
    }

    public function edit(JadwalUjian $jadwal)
    {
        $paket = PaketUjian::all();
        return view('pages.cbt.jadwal.create-edit-ajax', compact('jadwal', 'paket'));
    }

    public function update(StoreJadwalRequest $request, JadwalUjian $jadwal)
    {
        try {
            $jadwal->update($request->validated());
            logActivity('cbt', "Memperbarui jadwal ujian: {$jadwal->nama_kegiatan}", $jadwal);
            return jsonSuccess('Jadwal ujian berhasil diperbarui.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui jadwal ujian.');
        }
    }

    public function generateToken(JadwalUjian $jadwal)
    {
        try {
            $this->jadwalUjianService->generateToken($jadwal);
            return jsonSuccess('Token baru berhasil digenerate: ' . $jadwal->token_ujian);
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal generate token.');
        }
    }

    public function toggleToken(JadwalUjian $jadwal)
    {
        try {
            $this->jadwalUjianService->toggleToken($jadwal);
            return jsonSuccess('Status token berhasil diubah.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal mengubah status token.');
        }
    }

    public function destroy(JadwalUjian $jadwal)
    {
        try {
            $this->jadwalUjianService->delete($jadwal);
            return jsonSuccess('Jadwal ujian berhasil dihapus.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus jadwal ujian.');
        }
    }
}
