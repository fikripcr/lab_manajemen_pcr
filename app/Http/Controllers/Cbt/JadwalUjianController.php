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
            ->editColumn('waktu_mulai', fn($j) => formatTanggalIndo($j->waktu_mulai))
            ->editColumn('waktu_selesai', fn($j) => formatTanggalIndo($j->waktu_selesai))
            ->editColumn('token_ujian', function ($j) {
                $btnClass = $j->is_token_aktif ? 'badge bg-success text-white' : 'badge bg-secondary text-white';
                return '<span class="' . $btnClass . '">' . ($j->token_ujian ?? '-') . '</span>';
            })
            ->addColumn('action', function ($j) {
                $customActions = [
                    [
                        'url'        => 'javascript:void(0)',
                        'label'      => 'Generate Token Baru',
                        'icon'       => 'rotate',
                        'class'      => 'btn-jadwal-action',
                        'attributes' => 'data-url="' . route('cbt.jadwal.generate-token', $j) . '"',
                    ],
                    [
                        'url'        => 'javascript:void(0)',
                        'label'      => $j->is_token_aktif ? 'Nonaktifkan Token' : 'Aktifkan Token',
                        'icon'       => $j->is_token_aktif ? 'eye-off' : 'eye',
                        'class'      => 'btn-jadwal-action',
                        'attributes' => 'data-url="' . route('cbt.jadwal.toggle-token', $j) . '"',
                    ],
                ];

                if (auth()->user()->hasRole('admin')) {
                    $customActions[] = [
                        'url'   => route('cbt.execute.start', $j),
                        'label' => 'Test Ujian (Admin Bypass)',
                        'icon'  => 'play-card',
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
            ->rawColumns(['token_ujian', 'action'])
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
            return jsonSuccess('Jadwal ujian berhasil dibuat.', route('cbt.jadwal.index'));
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
            return jsonSuccess('Jadwal ujian berhasil diperbarui.', route('cbt.jadwal.index'));
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
