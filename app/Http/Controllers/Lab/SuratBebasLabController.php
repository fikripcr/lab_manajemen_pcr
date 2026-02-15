<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\SuratBebasLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SuratBebasLabController extends Controller
{
    public function index()
    {
        return view('pages.lab.surat-bebas.index');
    }

    public function data(Request $request)
    {
        $query = SuratBebasLab::with('student', 'approver')->latest();

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
                return "<span class='badge bg-{$color}'>" . ucfirst($row->status) . "</span>";
            })
            ->addColumn('tanggal', function ($row) {
                return $row->created_at->format('d M Y');
            })
            ->addColumn('action', function ($row) {
                $id  = $row->encrypted_id;
                $btn = '<a href="' . route('lab.surat-bebas.show', $id) . '" class="btn btn-sm btn-icon btn-outline-primary"><i class="bx bx-show"></i></a>';
                return $btn;
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
            return redirect()->route('lab.surat-bebas.show', $existing->encrypted_id)
                ->with('warning', 'Anda sudah memiliki pengajuan yang sedang diproses.');
        }

        return view('pages.lab.surat-bebas.create');
    }

    public function store(Request $request)
    {
        // Simple create, logic to check liabilities handles in Approval or Middleware?
        // Ideally we check here too but for now just submit.

        DB::transaction(function () use ($request) {
            $surat = SuratBebasLab::create([
                'student_id' => auth()->id(),
                'status'     => 'pending',
                'remarks'    => $request->remarks,
            ]);

            // Create Initial Approval (Pending)
            $approval = \App\Models\Lab\LabRiwayatApproval::create([
                'model'      => SuratBebasLab::class,
                'model_id'   => $surat->surat_bebas_lab_id,
                'status'     => 'pending',
                'keterangan' => 'Pengajuan baru',
                'created_by' => auth()->id(),
            ]);

            $surat->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);
        });

        return jsonSuccess('Pengajuan berhasil dikirim.', route('lab.surat-bebas.index'));
    }

    public function show($id)
    {
        $surat = SuratBebasLab::with(['student', 'approver', 'approvals'])->findOrFail(decryptId($id));
        return view('pages.lab.surat-bebas.show', compact('surat'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $surat = SuratBebasLab::findOrFail(decryptId($id));

        DB::transaction(function () use ($surat, $request) {
            $updateData = [
                'status'      => $request->status,
                'remarks'     => $request->remarks, // Legacy remarks column
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ];

            // If approved, generate PDF (Simulation)
            if ($request->status == 'approved') {
                // $updateData['file_path'] = 'surat-bebas/SB-'. $surat->id . '.pdf';
            }

            // Create New Approval Record
            $approval = \App\Models\Lab\LabRiwayatApproval::create([
                'model'      => SuratBebasLab::class,
                'model_id'   => $surat->surat_bebas_lab_id,
                'status'     => $request->status,
                'pejabat'    => auth()->user()->name,
                'keterangan' => $request->remarks,
                'created_by' => auth()->id(),
            ]);

            $updateData['latest_riwayatapproval_id'] = $approval->riwayatapproval_id;

            $surat->update($updateData);
        });

        return jsonSuccess('Status berhasil diperbarui.');
    }
}
