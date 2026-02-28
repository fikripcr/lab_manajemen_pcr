<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DokumenApprovalRequest;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\RiwayatApproval;
use App\Models\Shared\Personil;
use Illuminate\Support\Facades\DB;

class DokumenApprovalController extends Controller
{
    public function create(Dokumen $dokumen)
    {
        $pegawais = Personil::orderBy('nama')->get();
        return view('pages.pemutu.dokumens._approval_form', compact('dokumen', 'pegawais'));
    }

    public function store(DokumenApprovalRequest $request, Dokumen $dokumen)
    {

        DB::beginTransaction();

        // Standardization: terima -> Approved, tolak -> Rejected, tangguhkan -> Pending
        $statusMapping = [
            'terima'     => 'Approved',
            'tolak'      => 'Rejected',
            'tangguhkan' => 'Pending',
        ];

        $status = $statusMapping[$request->status] ?? 'Pending';

        $personil = Personil::findOrFail(decryptId($request->personil_id));

        $approval = RiwayatApproval::create([
            'model'    => Dokumen::class,
            'model_id' => $dokumen->dok_id,
            'status'   => $status,
            'pejabat'  => $personil->nama,
            'catatan'  => $request->komentar,
        ]);

        DB::commit();

        logActivity('pemutu', "Memberikan approval untuk dokumen: {$dokumen->judul} dengan status {$status}");

        return jsonSuccess('Approval berhasil disimpan');
    }

    public function destroy(RiwayatApproval $approval)
    {
        $dokName = $approval->subject?->judul ?? 'Dokumen';
        $approval->delete();

        logActivity('pemutu', "Menghapus approval untuk dokumen: {$dokName}");

        return jsonSuccess('Approval berhasil dihapus');
    }
}
