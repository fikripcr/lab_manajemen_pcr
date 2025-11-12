<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function home(){
        // Get the 6 most recent published announcements/news
        $recentNews = Pengumuman::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        return view('pages.guest.home', compact('recentNews'));
    }
    
    public function showNews(Pengumuman $pengumuman){
        // Only show published items
        if (!$pengumuman->is_published) {
            abort(404);
        }
        
        return view('pages.guest.news.show', compact('pengumuman'));
    }
}
