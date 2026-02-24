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
    public function __construct(protected JadwalUjianService $JadwalUjianService)
    {}

    public function index()
    {
        return view('pages.cbt.jadwal.index');
    }

    public function paginate(Request $request)
    {
        $query = $this->JadwalUjianService->getFilteredQuery($request->all());
        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('kegiatan_paket', function ($j) {
                return '<div class="d-flex flex-column gap-1">
                    <div class="fw-bold ">' . e($j->nama_kegiatan) . '</div>
                    <div class="text-muted small"><i class="ti ti-package me-1"></i>' . e($j->paket->nama_paket ?? '-') . '</div>
                </div>';
            })
            ->addColumn('token_info', function ($j) {
                $statusColor = $j->is_token_aktif ? 'success' : 'secondary';
                $icon        = $j->is_token_aktif ? 'ti-circle-check' : 'ti-circle-x';
                return '<div class="d-flex flex-column gap-1">
                    <div class="fw-bold font-monospace fs-3">' . e($j->token_ujian ?? '-') . '</div>
                    <div><i class="ti ' . $icon . ' text-' . $statusColor . ' fs-2" title="' . ($j->is_token_aktif ? 'Aktif' : 'Nonaktif') . '"></i></div>
                </div>';
            })
            ->addColumn('waktu_status', function ($j) {
                $now       = now();
                $isStarted = $now->gte($j->waktu_mulai);
                $isEnded   = $now->gte($j->waktu_selesai);

                if ($isEnded) {
                    $statusBadge = '<span class="badge bg-secondary-lt text-secondary badge-sm">Selesai</span>';
                } elseif ($isStarted) {
                    $statusBadge = '<span class="badge bg-success-lt text-success badge-sm">Berlangsung</span>';
                } else {
                    $statusBadge = '<span class="badge bg-azure-lt text-azure badge-sm">Akan Datang</span>';
                }

                return '<div class="d-flex flex-column">
                    <div class="small fw-bold">' . $j->waktu_mulai->format('d M Y') . ' (' . $j->waktu_mulai->format('H:i') . ' - ' . $j->waktu_selesai->format('H:i') . ')</div>
                    <div class="mt-1">' . $statusBadge . '</div>
                </div>';
            })
            ->addColumn('peserta', function ($j) {
                $jumlahPeserta     = $j->pesertaBerhak->count();
                $jumlahPelanggaran = $j->riwayatSiswa->sum(function ($r) {
                    return $r->pelanggaran_count;
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
            $this->JadwalUjianService->store($request->validated());
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
            $this->JadwalUjianService->generateToken($jadwal);
            return jsonSuccess('Token baru berhasil digenerate: ' . $jadwal->token_ujian);
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal generate token.');
        }
    }

    public function toggleToken(JadwalUjian $jadwal)
    {
        try {
            $this->JadwalUjianService->toggleToken($jadwal);
            return jsonSuccess('Status token berhasil diubah.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal mengubah status token.');
        }
    }

    public function destroy(JadwalUjian $jadwal)
    {
        try {
            $this->JadwalUjianService->delete($jadwal);
            return jsonSuccess('Jadwal ujian berhasil dihapus.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus jadwal ujian.');
        }
    }
}
