<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\Feedback;
use App\Models\Eoffice\Layanan;
use Illuminate\Http\Request;

class FeedbackService
{
    /**
     * Get paginated feedback data for DataTables.
     */
    public function getPaginateData(Request $request)
    {
        $query = Feedback::with(['layanan.jenisLayanan']);

        if ($request->filled('jenislayanan_id')) {
            $query->whereHas('layanan', function ($q) use ($request) {
                $q->where('jenislayanan_id', decryptId($request->jenislayanan_id));
            });
        }

        if ($request->filled('f_tgl_start') && $request->filled('f_tgl_end')) {
            $query->whereBetween('created_at', [
                $request->f_tgl_start,
                $request->f_tgl_end . ' 23:59:59',
            ]);
        }

        return $query;
    }

    /**
     * Store feedback for a layanan.
     */
    public function store(array $data)
    {
        return Feedback::create($data);
    }
}
