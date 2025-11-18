<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentationController extends Controller
{
    public function index()
    {
        // Read the README content from the project root
        $readmePath = base_path('README.md');

        if (!file_exists($readmePath)) {
            $htmlContent = '<h1>Documentation Not Found</h1><p>README.md file could not be found in the project root.</p>';
        } else {
            $readmeContent = file_get_contents($readmePath);

            if ($readmeContent === false) {
                $htmlContent = '<h1>Error Reading Documentation</h1><p>Could not read the README.md file.</p>';
            } else {
                // Convert Markdown to HTML using CommonMark
                $converter = new \League\CommonMark\CommonMarkConverter([
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                ]);

                $htmlContent = $converter->convert($readmeContent)->getContent();
            }
        }

        $lastUpdated = file_exists($readmePath) ? filemtime($readmePath) : null;

        return view('pages.sys.documentation.index', compact('htmlContent', 'lastUpdated'));
    }
}
