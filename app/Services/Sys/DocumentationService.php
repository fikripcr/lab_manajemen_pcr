<?php

namespace App\Services\Sys;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\MarkdownConverter;

class DocumentationService
{
    protected string $docsPath;

    protected array $allowedDirectories;

    protected array $categoryIcons = [
        'pemutu' => 'ti ti-target',
        'hr' => 'ti ti-users',
        'pmb' => 'ti ti-user-plus',
        'lab' => 'ti ti-flask',
        'sys' => 'ti ti-settings',
        'cms' => 'ti ti-article',
        'eoffice' => 'ti ti-mail',
        'cbt' => 'ti ti-monitor',
    ];

    protected array $categoryNames = [
        'pemutu' => 'Penjaminan Mutu (SPMI)',
        'hr' => 'Human Resource',
        'pmb' => 'Penerimaan Mahasiswa Baru',
        'lab' => 'Laboratorium',
        'sys' => 'System',
        'cms' => 'Content Management',
        'eoffice' => 'E-Office',
        'cbt' => 'Computer Based Test',
    ];

    public function __construct()
    {
        $this->docsPath = base_path('docs/');
        $this->allowedDirectories = [
            base_path('docs/'),
        ];
    }

    /**
     * Get all available documentation files with hierarchical structure
     */
    public function getAllDocumentation(?string $search = null): Collection
    {
        $docs = collect();

        $this->scanDirectory($this->docsPath, '', $docs);

        // Filter by search query if provided
        if ($search) {
            $docs = $docs->filter(function ($doc) use ($search) {
                return stripos($doc['title'], $search) !== false
                    || stripos($doc['name'], $search) !== false
                    || stripos($doc['category'], $search) !== false
                    || $this->searchInContent($doc['path'], $search);
            });
        }

        // Sort by category, then by order/sequence
        $docs = $docs->sortBy(function ($doc) {
            $categoryOrder = array_search($doc['category'], array_keys($this->categoryNames)) ?: 999;
            return [$categoryOrder, $doc['order'] ?? 999, $doc['title']];
        })->values();

        return $docs;
    }

    /**
     * Scan directory recursively for markdown files
     */
    protected function scanDirectory(string $directory, string $relativePath, Collection &$docs): void
    {
        if (! is_dir($directory)) {
            return;
        }

        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || $file === '.gitkeep') {
                continue;
            }

            $filePath = $directory . $file;
            $relativeFilePath = $relativePath . $file;

            if (is_dir($filePath)) {
                // Recursively scan subdirectory
                $this->scanDirectory($filePath . '/', $relativeFilePath . '/', $docs);
            } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'md') {
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $content = file_get_contents($filePath);

                $docs->push([
                    'name' => $relativeFilePath,
                    'filename' => $fileName,
                    'title' => $this->extractTitle($content) ?: $this->generateTitle($fileName),
                    'excerpt' => $this->extractExcerpt($content),
                    'path' => $filePath,
                    'relative_path' => $relativeFilePath,
                    'directory' => $relativePath,
                    'category' => $this->determineCategory($relativePath),
                    'lastUpdated' => filemtime($filePath),
                    'size' => filesize($filePath),
                    'order' => $this->extractOrder($fileName),
                    'depth' => substr_count($relativePath, '/'),
                ]);
            }
        }
    }

    /**
     * Get documentation by path
     */
    public function getDocumentation(string $path): ?array
    {
        // Prevent directory traversal
        $path = str_replace('..', '', $path);
        $path = str_replace('\\', '/', $path);

        // Ensure .md extension
        if (! str_ends_with($path, '.md')) {
            $path .= '.md';
        }

        // Remove leading slash if exists
        $path = ltrim($path, '/');

        $filePath = $this->docsPath . $path;

        // Security check: ensure file is within docs directory
        $realPath = realpath($filePath);
        $realDocsPath = realpath($this->docsPath);

        if ($realPath === false || $realDocsPath === false ||
            strpos($realPath, $realDocsPath) !== 0) {
            return null;
        }

        if (! file_exists($filePath)) {
            return null;
        }

        $content = file_get_contents($filePath);
        $fileName = pathinfo($path, PATHINFO_FILENAME);

        return [
            'name' => $path,
            'filename' => $fileName,
            'title' => $this->extractTitle($content) ?: $this->generateTitle($fileName),
            'content' => $content,
            'htmlContent' => $this->convertMarkdownToHtml($content),
            'path' => $filePath,
            'relative_path' => $path,
            'directory' => dirname($path) . '/',
            'category' => $this->determineCategory($path),
            'lastUpdated' => filemtime($filePath),
            'size' => filesize($filePath),
            'order' => $this->extractOrder($fileName),
            'depth' => substr_count($path, '/'),
            'excerpt' => $this->extractExcerpt($content),
        ];
    }

    /**
     * Get hierarchical tree structure of documentation
     */
    public function getTree(?string $search = null): array
    {
        $docs = $this->getAllDocumentation($search);

        $tree = [];

        foreach ($docs as $doc) {
            $category = $doc['category'];

            if (! isset($tree[$category])) {
                $tree[$category] = [
                    'name' => $category,
                    'display_name' => $this->categoryNames[$category] ?? ucfirst($category),
                    'icon' => $this->categoryIcons[$category] ?? 'ti ti-file',
                    'children' => [],
                    'path' => $doc['directory'],
                ];
            }

            $tree[$category]['children'][] = $doc;
        }

        return $tree;
    }

    /**
     * Get related documentation (same category or directory)
     */
    public function getRelatedDocumentation(string $currentPath, int $limit = 5): Collection
    {
        $current = $this->getDocumentation($currentPath);

        if (! $current) {
            return collect();
        }

        return $this->getAllDocumentation()
            ->filter(function ($doc) use ($current) {
                return $doc['name'] !== $current['name']
                    && ($doc['category'] === $current['category']
                        || $doc['directory'] === $current['directory']);
            })
            ->take($limit);
    }

    /**
     * Get previous and next documentation
     */
    public function getNavigation(string $currentPath): array
    {
        $docs = $this->getAllDocumentation();
        $current = $this->getDocumentation($currentPath);

        if (! $current) {
            return ['previous' => null, 'next' => null];
        }

        $currentIndex = $docs->search(function ($doc) use ($current) {
            return $doc['name'] === $current['name'];
        });

        if ($currentIndex === false) {
            return ['previous' => null, 'next' => null];
        }

        return [
            'previous' => $currentIndex > 0 ? $docs->get($currentIndex - 1) : null,
            'next' => $currentIndex < $docs->count() - 1 ? $docs->get($currentIndex + 1) : null,
        ];
    }

    /**
     * Get breadcrumb for documentation
     */
    public function getBreadcrumb(string $path): array
    {
        $breadcrumb = [
            ['label' => 'Documentation', 'url' => route('sys.documentation.index')],
        ];

        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');
        $segments = explode('/', $path);

        $currentPath = '';
        foreach ($segments as $index => $segment) {
            // Remove .md extension from last segment
            if ($index === count($segments) - 1) {
                $segment = str_replace('.md', '', $segment);
            }

            $currentPath .= ($currentPath ? '/' : '') . $segment;

            // Don't add link to current page
            if ($index === count($segments) - 1) {
                $breadcrumb[] = [
                    'label' => $this->generateTitle($segment),
                    'url' => null,
                ];
            } else {
                $breadcrumb[] = [
                    'label' => $this->generateTitle($segment),
                    'url' => route('sys.documentation.category', ['category' => $currentPath]),
                ];
            }
        }

        return $breadcrumb;
    }

    /**
     * Update documentation content
     */
    public function updateDocumentation(string $path, string $content): bool
    {
        $doc = $this->getDocumentation($path);

        if (! $doc) {
            return false;
        }

        // Check if file is writable
        if (! is_writable($doc['path'])) {
            \Log::error('Documentation file is not writable: ' . $doc['path']);
            return false;
        }

        // Write content
        $bytesWritten = file_put_contents($doc['path'], $content);

        if ($bytesWritten === false) {
            \Log::error('Failed to write documentation file: ' . $doc['path']);
            return false;
        }

        return true;
    }

    /**
     * Convert Markdown to HTML with proper syntax highlighting support
     */
    public function convertMarkdownToHtml(string $markdown): string
    {
        // Setup environment dengan konfigurasi custom
        $environment = new Environment([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 100,
        ]);

        // Add extensions - gunakan individual extensions untuk menghindari konflik
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new TableExtension());
        
        // GFM features (tanpa konflik delimiter)
        $environment->addExtension(new \League\CommonMark\Extension\TaskList\TaskListExtension());
        $environment->addExtension(new \League\CommonMark\Extension\Autolink\AutolinkExtension());
        $environment->addExtension(new \League\CommonMark\Extension\Strikethrough\StrikethroughExtension());

        // Create converter
        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($markdown)->getContent();

        // Post-process HTML to improve code blocks and diagrams
        $html = $this->postProcessHtml($html);

        return $html;
    }

    /**
     * Post-process HTML to improve rendering
     * - Add language classes to code blocks
     * - Convert mermaid code blocks to div.mermaid
     * - Improve tables
     */
    protected function postProcessHtml(string $html): string
    {
        // Fix code blocks: convert <pre><code class="language-x"> to <pre class="language-x"><code>
        $html = preg_replace_callback(
            '/<pre><code class="language-([^"]+)">(.*?)<\/code><\/pre>/s',
            function ($matches) {
                $language = $matches[1];
                $code = $matches[2];
                // Preserve whitespace in code
                return '<pre class="language-' . $language . '"><code class="language-' . $language . '">' . $code . '</code></pre>';
            },
            $html
        );

        // Handle code blocks without language class
        $html = preg_replace_callback(
            '/<pre><code>(.*?)<\/code><\/pre>/s',
            function ($matches) {
                $code = $matches[1];
                return '<pre class="language-none"><code>' . $code . '</code></pre>';
            },
            $html
        );

        // Convert mermaid code blocks to div.mermaid for better rendering
        $html = preg_replace_callback(
            '/<pre class="language-mermaid"><code class="language-mermaid">(.*?)<\/code><\/pre>/s',
            function ($matches) {
                $code = $matches[1];
                // Decode HTML entities
                $code = html_entity_decode($code);
                return '<div class="mermaid">' . $code . '</div>';
            },
            $html
        );

        // Add table-responsive wrapper and Bootstrap classes to tables
        $html = preg_replace(
            '/<table>/i',
            '<div class="table-responsive"><table class="table table-bordered table-striped table-hover table-sm">',
            $html
        );
        $html = str_replace('</table>', '</div></table>', $html);

        // Add Bootstrap classes to blockquotes
        $html = str_replace('<blockquote>', '<blockquote class="blockquote">', $html);

        // Improve inline code styling
        $html = preg_replace(
            '/<code>(?!.*<\/code>)/',
            '<code class="inline-code">',
            $html
        );

        return $html;
    }

    /**
     * Extract title from markdown content (first H1)
     */
    protected function extractTitle(string $content): ?string
    {
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    /**
     * Extract excerpt from markdown content (first paragraph after title)
     */
    protected function extractExcerpt(string $content): string
    {
        // Remove title
        $content = preg_replace('/^#\s+(.+)$/m', '', $content);

        // Get first non-empty line
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line && ! str_starts_with($line, '#')) {
                return Str::limit(strip_tags($line), 150);
            }
        }

        return 'No description available.';
    }

    /**
     * Generate title from filename
     */
    protected function generateTitle(string $filename): string
    {
        // Remove numeric prefix (e.g., 00-, 01-, 02-)
        $filename = preg_replace('/^\d+-/', '', $filename);

        // Replace separators with spaces
        $title = str_replace(['-', '_', '.'], ' ', $filename);

        // Capitalize words
        $title = ucwords($title);

        return $title;
    }

    /**
     * Extract order number from filename (e.g., 00-general-overview.md → 0)
     */
    protected function extractOrder(string $filename): int
    {
        if (preg_match('/^(\d+)-/', $filename, $matches)) {
            return (int) $matches[1];
        }

        return 999;
    }

    /**
     * Determine category from path
     */
    protected function determineCategory(string $path): string
    {
        $segments = explode('/', $path);

        // First segment is usually the category
        $firstSegment = strtolower($segments[0] ?? 'main');

        // Remove numeric prefix if exists
        $firstSegment = preg_replace('/^\d+-/', '', $firstSegment);

        return in_array($firstSegment, array_keys($this->categoryNames)) ? $firstSegment : 'main';
    }

    /**
     * Search in file content
     */
    protected function searchInContent(string $filePath, string $query): bool
    {
        $content = file_get_contents($filePath);

        return stripos($content, $query) !== false;
    }

    /**
     * Get documentation statistics
     */
    public function getStatistics(): array
    {
        $docs = $this->getAllDocumentation();

        return [
            'total' => $docs->count(),
            'by_category' => $docs->groupBy('category')->map->count()->toArray(),
            'total_size' => $docs->sum('size'),
            'last_updated' => $docs->max('lastUpdated'),
        ];
    }
}
