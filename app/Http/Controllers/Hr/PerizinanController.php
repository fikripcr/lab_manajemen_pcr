<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\PerizinanApproveRequest;
use App\Http\Requests\Hr\PerizinanRequest;
use App\Models\Hr\JenisIzin;
use App\Models\Hr\Perizinan;
use App\Models\Shared\Pegawai;
use App\Services\Hr\PerizinanService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PerizinanController extends Controller
{
    public function __construct(
        protected PerizinanService $perizinanService
    ) {}

    public function index(Request $request)
    {
        $years = Perizinan::selectRaw('YEAR(tgl_awal) as year')
            ->whereNotNull('tgl_awal')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        $selectedYear = $request->input('year', date('Y'));
        $pageTitle    = 'Data Perizinan';

        return view('pages.hr.perizinan.index', compact('years', 'selectedYear', 'pageTitle'));
    }

    public function data(Request $request)
    {
        $year   = $request->input('year', date('Y'));
        $status = $request->input('status');

        $query = Perizinan::with(['jenisIzin', 'pengusulPegawai.latestDataDiri', 'latestApproval']);

        if ($year && $year !== 'all') {
            $query->whereYear('tgl_awal', $year);
        }

        if ($status && $status !== 'all') {
            $query->whereHas('latestApproval', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_pegawai', function ($row) {
                return hrPegawaiName($row->pengusulPegawai);
            })
            ->addColumn('jenis_izin', function ($row) {
                return $row->jenisIzin?->nama ?? '-';
            })
            ->addColumn('tanggal', function ($row) {
                return hrDateRange($row->tgl_awal, $row->tgl_akhir);
            })
            ->addColumn('status', function ($row) {
                return hrStatusBadge($row->status);
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl'   => route('hr.perizinan.show', $row->encrypted_perizinan_id),
                    'viewModal' => false,
                    'viewTitle' => 'Detail Perizinan',
                    'editUrl'   => in_array($row->status, ['Draft', 'Diajukan']) ? route('hr.perizinan.edit', $row->encrypted_perizinan_id) : null,
                    'editModal' => true,
                    'editTitle' => 'Edit Perizinan',
                    'deleteUrl' => in_array($row->status, ['Draft', 'Diajukan']) ? route('hr.perizinan.destroy', $row->encrypted_perizinan_id) : null,
                ])->render();
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $jenisIzin = JenisIzin::active()->get();
        $pegawais  = Pegawai::with('latestDataDiri')->get();
        $perizinan = new Perizinan();
        return view('pages.hr.perizinan.create-edit-ajax', compact('jenisIzin', 'pegawais', 'perizinan'));
    }

    public function store(PerizinanRequest $request)
    {
        $this->perizinanService->store($request->validated());

        return jsonSuccess('Perizinan berhasil dibuat.');
    }

    public function show(Perizinan $perizinan)
    {
        $perizinan->load(['jenisIzin', 'pengusulPegawai.latestDataDiri', 'approvalHistory']);
        return view('pages.hr.perizinan.show', compact('perizinan'));
    }

    public function edit(Perizinan $perizinan)
    {
        $jenisIzin = JenisIzin::active()->get();
        $pegawais  = Pegawai::with('latestDataDiri')->get();
        $perizinan->load(['pengusulPegawai.latestDataDiri']);
        return view('pages.hr.perizinan.create-edit-ajax', compact('perizinan', 'jenisIzin', 'pegawais'));
    }

    public function update(PerizinanRequest $request, Perizinan $perizinan)
    {
        if (! in_array($perizinan->status, ['Draft', 'Diajukan'])) {
            return jsonError('Perizinan tidak dapat diubah karena sudah diproses.');
        }

        $this->perizinanService->update($perizinan, $request->validated());
        return jsonSuccess('Perizinan berhasil diperbarui.');
    }

    public function destroy(Perizinan $perizinan)
    {
        if (! in_array($perizinan->status, ['Draft', 'Diajukan'])) {
            return jsonError('Perizinan tidak dapat dihapus karena sudah diproses.');
        }

        $perizinan->delete();

        return jsonSuccess('Perizinan berhasil dihapus.');
    }

    /**
     * Approve perizinan
     */
    public function approve(PerizinanApproveRequest $request, Perizinan $perizinan)
    {
        $this->perizinanService->approve($perizinan, $request->validated());

        $statusText = [
            'Approved' => 'disetujui',
            'Rejected' => 'ditolak',
            'Pending'  => 'ditangguhkan',
        ];

        return jsonSuccess('Perizinan berhasil di-' . ($statusText[$request->status] ?? 'proses'), route('hr.perizinan.show', $perizinan->encrypted_perizinan_id));
    }
}
