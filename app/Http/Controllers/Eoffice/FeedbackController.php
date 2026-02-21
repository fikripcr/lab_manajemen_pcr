<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\FeedbackStoreRequest;
use App\Models\Eoffice\JenisLayanan;
use App\Services\Eoffice\FeedbackService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FeedbackController extends Controller
{
    public function __construct(protected FeedbackService $feedbackService)
    {}

    /**
     * Feedback listing page.
     */
    public function index()
    {
        $pageTitle        = 'Feedback Layanan';
        $jenisLayananList = JenisLayanan::where('is_active', true)->orderBy('nama_layanan')->get();

        return view('pages.eoffice.feedback.index', compact('pageTitle', 'jenisLayananList'));
    }

    /**
     * Store feedback for a layanan.
     */
    public function store(FeedbackStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->feedbackService->store($validated);
            return jsonSuccess('Feedback berhasil disimpan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * DataTables data endpoint for feedback.
     */
    public function data(Request $request)
    {
        $query = $this->feedbackService->getPaginateData($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_layanan', function ($row) {
                return $row->layanan->jenisLayanan->nama_layanan ?? '-';
            })
            ->addColumn('no_layanan', function ($row) {
                if (! $row->layanan) {
                    return '-';
                }

                return '<a href="' . route('eoffice.layanan.show', $row->layanan->hashid) . '">' . $row->layanan->no_layanan . '</a>';
            })
            ->addColumn('action', function ($row) {
                if (! $row->layanan) {
                    return '-';
                }

                return '<a href="' . route('eoffice.layanan.show', $row->layanan->hashid) . '" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-eye"></i> Detail
                        </a>';
            })
            ->addColumn('rating_stars', function ($row) {
                $stars = str_repeat('<i class="ti ti-star-filled text-yellow"></i>', $row->rating);
                $empty = str_repeat('<i class="ti ti-star text-muted"></i>', 5 - $row->rating);
                return $stars . $empty;
            })
            ->addColumn('tanggal', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y H:i') : '-';
            })
            ->rawColumns(['rating_stars', 'no_layanan', 'action'])
            ->make(true);
    }
}
