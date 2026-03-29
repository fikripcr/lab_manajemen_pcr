<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\DocumentationUpdateRequest;
use App\Services\Sys\DocumentationService;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function __construct(
        protected DocumentationService $documentationService
    ) {}

    /**
     * Display documentation index with search and categories
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');

        $docs = $this->documentationService->getAllDocumentation($search);

        // Filter by category if provided
        if ($category) {
            $docs = $docs->where('category', $category);
        }

        $tree = $this->documentationService->getTree($search);
        $stats = $this->documentationService->getStatistics();

        return view('pages.sys.documentation.index', [
            'pageTitle' => 'Documentation',
            'docs' => $docs,
            'tree' => $tree,
            'stats' => $stats,
            'search' => $search,
            'currentCategory' => $category,
        ]);
    }

    /**
     * Display documentation by category
     */
    public function category(Request $request, string $category)
    {
        $category = rtrim($category, '/');
        $search = $request->get('search');
        
        $docs = $this->documentationService->getAllDocumentation($search)
            ->where('category', $category);

        $tree = $this->documentationService->getTree($search);

        return view('pages.sys.documentation.index', [
            'pageTitle' => $this->documentationService->getTree()[$category]['display_name'] ?? ucfirst($category),
            'docs' => $docs,
            'tree' => $tree,
            'stats' => $this->documentationService->getStatistics(),
            'search' => $search,
            'currentCategory' => $category,
        ]);
    }

    /**
     * Show documentation content
     */
    public function show(Request $request, string $path = 'index')
    {
        // Clean path
        $path = rtrim($path, '/');

        $doc = $this->documentationService->getDocumentation($path);

        if (! $doc) {
            abort(404, 'Documentation not found');
        }

        $breadcrumb = $this->documentationService->getBreadcrumb($path);
        $navigation = $this->documentationService->getNavigation($path);
        $related = $this->documentationService->getRelatedDocumentation($path);

        // Handle AJAX requests for modal view
        if ($request->ajax() || $request->has('ajax')) {
            return view('pages.sys.documentation.show-modal', [
                'doc' => $doc,
                'htmlContent' => $doc['htmlContent'],
            ]);
        }

        return view('pages.sys.documentation.show', [
            'doc' => $doc,
            'pageTitle' => $doc['title'],
            'htmlContent' => $doc['htmlContent'],
            'breadcrumb' => $breadcrumb,
            'navigation' => $navigation,
            'related' => $related,
            'fileName' => $doc['filename'],
            'filePath' => $path,
        ]);
    }

    /**
     * Show the documentation edit form
     */
    public function edit(string $path = 'index')
    {
        $path = rtrim($path, '/');
        $doc = $this->documentationService->getDocumentation($path);

        if (! $doc) {
            abort(404, 'Documentation not found');
        }

        $breadcrumb = $this->documentationService->getBreadcrumb($path);

        return view('pages.sys.documentation.edit', [
            'doc' => $doc,
            'content' => $doc['content'],
            'path' => $path,
            'pageTitle' => 'Edit - ' . $doc['title'],
            'breadcrumb' => $breadcrumb,
        ]);
    }

    /**
     * Update the documentation file
     */
    public function update(DocumentationUpdateRequest $request, string $path = 'index')
    {
        $path = rtrim($path, '/');
        $content = $request->input('content');

        $success = $this->documentationService->updateDocumentation($path, $content);

        if (! $success) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update documentation. Check file permissions.');
        }

        // Log activity
        logActivity('documentation', 'Updated documentation: ' . $path);

        return redirect()->route('sys.documentation.show', ['path' => $path])
            ->with('success', 'Documentation updated successfully.');
    }

    /**
     * Search documentation
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (! $query || strlen($query) < 2) {
            return redirect()->route('sys.documentation.index')
                ->with('error', 'Please enter at least 2 characters to search.');
        }

        $docs = $this->documentationService->getAllDocumentation($query);

        return view('pages.sys.documentation.search', [
            'pageTitle' => 'Search Results: ' . $query,
            'docs' => $docs,
            'query' => $query,
            'tree' => $this->documentationService->getTree(),
        ]);
    }
}
