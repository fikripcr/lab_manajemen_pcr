<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Requests\Pemutu\DokumenApprovalRequest;
use App\Models\Pemutu\Dokumen;
use Illuminate\Support\Facades\DB;

class DokumenApprovalController extends Controller
{
    public function create(Dokumen $dokumen)
    {
        $personils = Personil::orderBy('nama')->get();
        return view('pages.pemutu.dokumens._approval_form', compact('dokumen', 'personils'));
    }

    public function store(DokumenApprovalRequest $request, Dokumen $dokumen)
    {
        try {
            DB::beginTransaction();

            $approval = DokumenApproval::create([
                'dok_id'     => $dokumen->dok_id,
                'pegawai_id' => $request->approver_id,
                'proses'     => 'Legalitas Dokumen',
            ]);

            DokumenApprovalStatus::create([
                'dokapproval_id'  => $approval->dokapproval_id,
                'status_approval' => $request->status,
                'komentar'        => $request->komentar,
            ]);

            DB::commit();

            logActivity('pemutu', "Memberikan approval untuk dokumen: {$dokumen->judul}");

            return jsonSuccess('Approval berhasil disimpan');
        } catch (Exception $e) {
            DB::rollBack();
            logError($e);
            return jsonError('Gagal menyimpan approval: ' . $e->getMessage());
        }
    }

    public function destroy(DokumenApproval $approval)
    {
        try {
            // Check if user is authorized (must be the approver)
            if ($approval->approver && $approval->approver->user_id !== auth()->id()) {
                return jsonError('Anda tidak memiliki hak akses untuk menghapus approval ini.', 403);
            }

            $dokName = $approval->dokumen?->judul;
            $approval->delete();

            logActivity('pemutu', "Menghapus approval untuk dokumen: {$dokName}");

            return jsonSuccess('Approval berhasil dihapus');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus approval: ' . $e->getMessage());
        }
    }
}
