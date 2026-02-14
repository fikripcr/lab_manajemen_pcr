<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\DokumenApproval;
use App\Models\Pemutu\DokumenApprovalStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokumenApprovalController extends Controller
{
    public function store(Request $request, Dokumen $dokumen)
    {
        $request->validate([
            'approver_id' => 'required|exists:pemutu_personil,personil_id',
            'status'      => 'required|in:terima,tolak,tangguhkan',
            'komentar'    => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create or update approval request
            // For now, simpler: one approval record per personil per document?
            // Or many approval records? The user said "setiap approval ada status nya"

            $approval = DokumenApproval::create([
                'dok_id'     => $dokumen->dok_id,
                'pegawai_id' => $request->approver_id,
                'proses'     => 'Legalitas Dokumen', // Default process name
                                                     // jabatan can be fetched from personil if needed
            ]);

            DokumenApprovalStatus::create([
                'dokapproval_id'  => $approval->dokapproval_id,
                'status_approval' => $request->status,
                'komentar'        => $request->komentar,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Approval berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan approval: ' . $e->getMessage(),
            ], 500);
        }
    }
}
