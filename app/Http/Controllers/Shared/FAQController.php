<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\FAQRequest;
use App\Models\Shared\FAQ;
use App\Services\Shared\FAQService;
use Exception;

class FAQController extends Controller
{
    protected $FAQService;

    public function __construct(FAQService $FAQService)
    {
        $this->FAQService = $FAQService;
    }

    public function index()
    {
        $faqs = FAQ::orderBy('category')->orderBy('seq')->get()->groupBy('category');
        return view('pages.shared.faq.index', compact('faqs'));
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
