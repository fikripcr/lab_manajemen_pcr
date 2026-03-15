<?php
namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\Pengumuman;
use App\Models\Cms\Slideshow;

class DashboardController extends Controller
{
    public function index()
    {
        // Slideshow
        $slideshows = Slideshow::where('is_active', true)
            ->orderBy('seq', 'asc')
            ->get();

        // Slideshow
        $slideshows = Slideshow::where('is_active', true)
            ->orderBy('seq', 'asc')
            ->get();

        // 4 Latest News
        $recentNews = Pengumuman::where('is_published', true)
            ->where('jenis', 'artikel_berita')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // 5 Latest Announcements for Timeline
        $recentAnnouncements = Pengumuman::where('is_published', true)
            ->where('jenis', 'cms_pengumuman')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.cms.dashboard', compact('slideshows', 'recentNews', 'recentAnnouncements'));
    }
}
