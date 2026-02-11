<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\Layanan;
use App\Models\Eoffice\LayananDiskusi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LayananDiskusiService
{
    /**
     * Store a new discussion message for a layanan.
     */
    public function store($layananId, array $data)
    {
        return DB::transaction(function () use ($layananId, $data) {
            $layanan = Layanan::findOrFail($layananId);
            $user    = Auth::user();

            $diskusi = LayananDiskusi::create([
                'layanan_id'       => $layananId,
                'user_id'          => $user->id,
                'pesan'            => $data['pesan'],
                'file_lampiran'    => $data['file_lampiran'] ?? null,
                'status_pengirim'  => $data['status_pengirim'] ?? null,
                'created_by_email' => $user->email,
            ]);

            // TODO: Integrate with notification system when available.
            // Original e-office sends email notifications to all participants.

            return $diskusi;
        });
    }
}
