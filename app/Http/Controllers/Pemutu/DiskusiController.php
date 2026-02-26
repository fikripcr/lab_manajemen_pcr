<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\Diskusi;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Services\Pemutu\DiskusiService;
use Illuminate\Http\Request;

class DiskusiController extends Controller
{
    public function __construct(
        protected DiskusiService $DiskusiService,
    ) {}

    /**
     * Kirim pesan diskusi baru untuk AMI.
     */
    public function storeAmi(Request $request, IndikatorOrgUnit $indOrg)
    {
        $request->validate([
            'isi' => ['required', 'string', 'max:5000'],
            'attachment_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx', 'max:5120'],
        ]);

        $this->DiskusiService->store(
            $indOrg,
            $request->all(),
            $request->file('attachment_file')
        );

        return jsonSuccess('Pesan diskusi berhasil dikirim.', route('pemutu.ami.detail', $indOrg->encrypted_indorgunit_id));
    }

    /**
     * Unduh lampiran diskusi.
     */
    public function download($id)
    {
        $realId = decryptIdIfEncrypted($id);
        
        $diskusi = Diskusi::findOrFail($realId);

        return downloadStorageFile($diskusi->attachment_file, logActivity: true);
    }
}
