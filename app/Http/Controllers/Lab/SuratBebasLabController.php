<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\SuratBebasLabRequest;
use App\Models\Lab\SuratBebasLab;
use App\Services\Lab\SuratBebasLabService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SuratBebasLabController extends Controller
{
    public function __construct(protected SuratBebasLabService $suratBebasLabService)
    {}

    public function index()
    {
        return view('pages.lab.surat-bebas.index');
    }

    public function data(Request $request)
    {
        $query = $this->suratBebasLabService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('mahasiswa', function ($row) {
                return $row->student->name . '<br><small>' . ($row->student->username ?? '-') . '</small>';
            })
            ->addColumn('status', function ($row) {
                $badges = [
                    'pending'  => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                ];
                $color = $badges[$row->status] ?? 'secondary';
                return "<span class='badge bg-{$color} text-white'>" . ucfirst($row->status) . "</span>";
            })
            ->addColumn('tanggal', function ($row) {
                return $row->created_at->format('d M Y');
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl' => route('lab.surat-bebas.show', $row->encrypted_surat_bebas_lab_id),
                ])->render();
            })
            ->rawColumns(['mahasiswa', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        // Check if user already has pending request
        $existing = SuratBebasLab::where('student_id', auth()->id())
            ->whereIn('status', ['pending'])
            ->first();

        if ($existing) {
            return redirect()->route('lab.surat-bebas.show', $existing->encrypted_surat_bebas_lab_id)
                ->with('warning', 'Anda sudah memiliki pengajuan yang sedang diproses.');
        }

        $surat = new SuratBebasLab();
        return view('pages.lab.surat-bebas.create-edit-ajax', compact('surat'));
    }

    public function store(SuratBebasLabRequest $request)
    {
        try {
            $this->suratBebasLabService->createRequest($request->validated() ?: $request->all());
            return jsonSuccess('Pengajuan berhasil dikirim.', route('lab.surat-bebas.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal mengirim pengajuan: ' . $e->getMessage());
        }
    }

    public function show(SuratBebasLab $suratBeba)
    {
        $surat = $suratBeba->load(['student', 'approver', 'approvals']);
        return view('pages.lab.surat-bebas.show', compact('surat'));
    }

    public function updateStatus(SuratBebasLabRequest $request, SuratBebasLab $suratBeba)
    {
        try {
            $this->suratBebasLabService->updateStatus($suratBeba, $request->validated());
            return jsonSuccess('Status berhasil diperbarui.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}
