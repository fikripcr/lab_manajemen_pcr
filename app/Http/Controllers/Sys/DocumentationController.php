<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Table\TableExtension;

class DocumentationController extends Controller
{
    protected $docsPath;

    public function __construct()
    {
        $this->docsPath = base_path('docs/');
    }

    public function index()
    {
        $indexPath = $this->docsPath . 'index.md';

        if (!file_exists($indexPath)) {
            $htmlContent = '<h1>Documentation Not Found</h1><p>Documentation index file could not be found in the docs directory.</p>';
        } else {
            $indexContent = file_get_contents($indexPath);

            if ($indexContent === false) {
                $htmlContent = '<h1>Error Reading Documentation</h1><p>Could not read the documentation index file.</p>';
            } else {
                $htmlContent = $this->convertMarkdownToHtml($indexContent);
            }
        }

        $lastUpdated = file_exists($indexPath) ? filemtime($indexPath) : null;

        return view('pages.sys.documentation.index', [
            'htmlContent' => $htmlContent,
            'lastUpdated' => $lastUpdated,
            'pageTitle' => 'Documentation Index'
        ]);
    }

    public function show($page = 'index')
    {
        // Sanitize the page parameter to prevent directory traversal
        $page = basename($page);

        // Ensure the file has .md extension
        if (!str_ends_with($page, '.md')) {
            $page .= '.md';
        }

        $filePath = $this->docsPath . $page;

        // Check if the file exists and is within the docs directory
        if (!file_exists($filePath) || strpos(realpath($filePath), realpath($this->docsPath)) !== 0) {
            abort(404, 'Documentation page not found');
        }

        $content = file_get_contents($filePath);
        $htmlContent = $this->convertMarkdownToHtml($content);

        // Generate page title from filename
        $pageTitle = $this->generatePageTitle($page);

        $lastUpdated = filemtime($filePath);

        return view('pages.sys.documentation.show', [
            'htmlContent' => $htmlContent,
            'lastUpdated' => $lastUpdated,
            'pageTitle' => $pageTitle
        ]);
    }

    /**
     * Convert Markdown to HTML
     */
    protected function convertMarkdownToHtml($markdown)
    {
        // Create a new CommonMark converter with GFM and table extensions
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        // Add extensions for GitHub Flavored Markdown support
        $environment = $converter->getEnvironment();
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new TableExtension());

        return $converter->convert($markdown)->getContent();
    }

    /**
     * Generate page title from filename
     */
    protected function generatePageTitle($filename)
    {
        // Remove .md extension and convert to title case
        $title = str_replace('.md', '', $filename);
        $title = str_replace(['-', '_'], ' ', $title);
        $title = ucwords($title);

        return $title;
    }
}
