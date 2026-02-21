<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Shared\Pengumuman;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.shared.pengumuman.index', compact('pengumumans'));
    }

    public function show(Pengumuman $pengumuman)
    {
        return view('pages.shared.pengumuman.show', compact('pengumuman'));
    }
}
