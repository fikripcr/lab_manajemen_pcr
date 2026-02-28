<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\Diskusi;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Services\Pemutu\DiskusiService;
use App\Http\Requests\Pemutu\DiskusiRequest;

class DiskusiController extends Controller
{
    public function __construct(
        protected DiskusiService $DiskusiService,
    ) {}

    /**
     * Kirim pesan diskusi baru untuk AMI.
     */
    public function storeAmi(DiskusiRequest $request, IndikatorOrgUnit $indOrg)
    {

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
