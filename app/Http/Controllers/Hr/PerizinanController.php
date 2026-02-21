<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
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
        protected \App\Services\Hr\PerizinanService $perizinanService
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

        $query = Perizinan::with(['jenisIzin', 'pengusulPegawai.latestDataDiri', 'latestApproval'])
            ->whereYear('tgl_awal', $year);

        if ($status) {
            $query->whereHas('latestApproval', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_pegawai', function ($row) {
                $dataDiri = $row->pengusulPegawai?->latestDataDiri;
                return $dataDiri ? ($dataDiri->inisial . ' - ' . $dataDiri->nama) : 'N/A';
            })
            ->addColumn('jenis_izin', function ($row) {
                return $row->jenisIzin?->nama ?? '-';
            })
            ->addColumn('tanggal', function ($row) {
                $awal  = $row->tgl_awal?->format('d/m/Y') ?? '-';
                $akhir = $row->tgl_akhir?->format('d/m/Y') ?? '-';
                return $awal . ' s/d ' . $akhir;
            })
            ->addColumn('status', function ($row) {
                $status = $row->status;
                $badges = [
                    'Draft'    => 'bg-secondary-lt',
                    'Pending'  => 'bg-warning-lt',
                    'Approved' => 'bg-success-lt',
                    'Rejected' => 'bg-danger-lt',
                ];
                $badge = $badges[$status] ?? 'bg-secondary-lt';
                return '<span class="badge ' . $badge . '">' . $status . '</span>';
            })
            ->addColumn('action', function ($row) {
                return view('pages.hr.perizinan._action', compact('row'))->render();
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
        try {
            $this->perizinanService->store($request->validated());

            return jsonSuccess('Perizinan berhasil dibuat.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal membuat perizinan: ' . $e->getMessage());
        }
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
        if ($perizinan->status !== 'Draft') {
            return jsonError('Perizinan tidak dapat diubah karena sudah diproses.');
        }

        try {
            $this->perizinanService->update($perizinan, $request->validated());

            return jsonSuccess('Perizinan berhasil diperbarui.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui perizinan: ' . $e->getMessage());
        }
    }

    public function destroy(Perizinan $perizinan)
    {
        if ($perizinan->status !== 'Draft') {
            return jsonError('Perizinan tidak dapat dihapus karena sudah diproses.');
        }

        try {
            $perizinan->delete();

            return jsonSuccess('Perizinan berhasil dihapus.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus perizinan: ' . $e->getMessage());
        }
    }
}
