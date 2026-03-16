<?php
namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\FAQRequest;
use App\Models\Cms\FAQ;
use App\Services\Cms\FAQService;

class FAQController extends Controller
{
    public function __construct(protected FAQService $faqService)
    {}

    public function index()
    {
        $faqs = $this->faqService->getAllGrouped();
        return view('pages.cms.faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('pages.cms.faq.create-edit-ajax', ['faq' => new FAQ()]);
    }

    public function store(FAQRequest $request)
    {
        $this->faqService->createFAQ($request->validated());
        return jsonSuccess('FAQ berhasil ditambahkan.', route('cms.faq.index'));
    }

    public function edit(FAQ $faq)
    {
        return view('pages.cms.faq.create-edit-ajax', compact('faq'));
    }

    public function update(FAQRequest $request, FAQ $faq)
    {
        $this->faqService->updateFAQ($faq, $request->validated());
        return jsonSuccess('FAQ berhasil diperbarui.', route('cms.faq.index'));
    }

    public function destroy(FAQ $faq)
    {
        $this->faqService->deleteFAQ($faq);
        return jsonSuccess('FAQ berhasil dihapus.', route('cms.faq.index'));
    }

    public function reorder(\App\Http\Requests\Cms\ReorderRequest $request)
    {
        $order = $request->validated()['order'] ?? [];
        if ($order) {
            $this->faqService->reorderFAQs($order);
            return jsonSuccess('Urutan FAQ berhasil diperbarui.');
        }
        return jsonError('Data urutan tidak valid.');
    }
}
