<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Shared\Pengumuman;

class DashboardController extends Controller
{
    public function index()
    {
        // Welcome message and recent announcements
        $recentNews = Pengumuman::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.shared.dashboard', compact('recentNews'));
    }
}
