<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\FAQRequest;
use App\Models\Shared\FAQ;
use App\Services\Shared\FAQService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FAQController extends Controller
{
    protected $FAQService;

    public function __construct(FAQService $FAQService)
    {
        $this->FAQService = $FAQService;
    }

    public function index()
    {
        return view('pages.shared.faq.index');
    }

    public function paginate(Request $request)
    {
        $query = $this->FAQService->getFilteredQuery($request->all());
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success text-white">Aktif</span>'
                    : '<span class="badge bg-secondary text-white">Draft</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('shared.faq.edit', $row->hashid),
                    'editModal' => true,
                    'deleteUrl' => route('shared.faq.destroy', $row->hashid),
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.shared.faq.create-edit-ajax', ['faq' => new FAQ()]);
    }

    public function store(FAQRequest $request)
    {
        try {
            $this->FAQService->createFAQ($request->validated());
            return jsonSuccess('FAQ berhasil ditambahkan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $id  = decryptIdIfEncrypted($id);
        $faq = FAQ::findOrFail($id);
        return view('pages.shared.faq.create-edit-ajax', compact('faq'));
    }

    public function update(FAQRequest $request, $id)
    {
        $id = decryptIdIfEncrypted($id);
        try {
            $this->FAQService->updateFAQ($id, $request->validated());
            return jsonSuccess('FAQ berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        $id = decryptIdIfEncrypted($id);
        try {
            $this->FAQService->deleteFAQ($id);
            return jsonSuccess('FAQ berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
