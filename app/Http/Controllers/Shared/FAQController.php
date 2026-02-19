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
        $faqs = $this->FAQService->getAllGrouped();
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
            return jsonSuccess('FAQ berhasil ditambahkan.', route('shared.faq.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(FAQ $faq)
    {
        return view('pages.shared.faq.create-edit-ajax', compact('faq'));
    }

    public function update(FAQRequest $request, FAQ $faq)
    {
        try {
            $this->FAQService->updateFAQ($faq, $request->validated());
            return jsonSuccess('FAQ berhasil diperbarui.', route('shared.faq.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(FAQ $faq)
    {
        try {
            $this->FAQService->deleteFAQ($faq);
            return jsonSuccess('FAQ berhasil dihapus.', route('shared.faq.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
    public function reorder(\Illuminate\Http\Request $request)
    {
        $order = $request->input('order');
        if (is_array($order)) {
            $this->FAQService->reorderFAQs($order);
            return jsonSuccess('Urutan FAQ berhasil diperbarui.');
        }
        return jsonError('Data urutan tidak valid.');
    }
}
