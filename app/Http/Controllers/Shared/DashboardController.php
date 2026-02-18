<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Shared\Pengumuman;
use App\Models\Shared\Slideshow;

class DashboardController extends Controller
{
    public function index()
    {
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
            ->where('jenis', 'pengumuman')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.shared.dashboard', compact('slideshows', 'recentNews', 'recentAnnouncements'));
    }
}
