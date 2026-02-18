<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\PerizinanStoreRequest;
use App\Http\Requests\Hr\PerizinanUpdateRequest;
use App\Models\Hr\JenisIzin;
use App\Models\Hr\Perizinan;
use App\Models\Hr\RiwayatApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PerizinanController extends Controller
{
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

        $selectedYear = $request->get('year', date('Y'));

        return view('pages.hr.perizinan.index', compact('years', 'selectedYear'));
    }

    public function data(Request $request)
    {
        $year   = $request->get('year', date('Y'));
        $status = $request->get('status');

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
        return view('pages.hr.perizinan.create', compact('jenisIzin'));
    }

    public function store(PerizinanStoreRequest $request)
    {
        $validated = $request->validated();

        // Create perizinan
        $perizinan = Perizinan::create([
            'jenisizin_id'           => $validated['jenisizin_id'],
            'pengusul'               => $validated['pengusul'],
            'pekerjaan_ditinggalkan' => $validated['pekerjaan_ditinggalkan'] ?? null,
            'keterangan'             => $validated['keterangan'] ?? null,
            'alamat_izin'            => $validated['alamat_izin'] ?? null,
            'tgl_awal'               => $validated['tgl_mulai'],
            'tgl_akhir'              => $validated['tgl_selesai'],
            'jam_awal'               => $validated['jam_awal'] ?? null,
            'jam_akhir'              => $validated['jam_akhir'] ?? null,
            'periode'                => date('Y'),
        ]);

        // Create initial approval record
        $approval = RiwayatApproval::create([
            'model'            => 'Perizinan',
            'model_id'         => $perizinan->perizinan_id,
            'status'           => 'Draft',
            'created_by_email' => Auth::user()?->email,
        ]);

        // Link approval to perizinan
        $perizinan->update([
            'latest_riwayatapproval_id' => $approval->riwayatapproval_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Perizinan berhasil dibuat.',
        ]);
    }

    public function show(Perizinan $perizinan)
    {
        $perizinan->load(['jenisIzin', 'pengusulPegawai.latestDataDiri', 'approvalHistory']);
        return view('pages.hr.perizinan.show', compact('perizinan'));
    }

    public function edit(Perizinan $perizinan)
    {
        $jenisIzin = JenisIzin::active()->get();
        $perizinan->load(['pengusulPegawai.latestDataDiri']);
        return view('pages.hr.perizinan.edit', compact('perizinan', 'jenisIzin'));
    }

    public function update(PerizinanUpdateRequest $request, Perizinan $perizinan)
    {
        // Only allow update if still Draft
        if ($perizinan->status !== 'Draft') {
            return response()->json([
                'success' => false,
                'message' => 'Perizinan tidak dapat diubah karena sudah diproses.',
            ], 400);
        }

        $perizinan->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Perizinan berhasil diperbarui.',
        ]);
    }

    public function destroy(Perizinan $perizinan)
    {
        // Only allow delete if still Draft
        if ($perizinan->status !== 'Draft') {
            return response()->json([
                'success' => false,
                'message' => 'Perizinan tidak dapat dihapus karena sudah diproses.',
            ], 400);
        }

        $perizinan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perizinan berhasil dihapus.',
        ]);
    }
}
