<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\Inventaris;
use App\Models\Lab\Kegiatan;
use App\Models\Lab\Lab;
use App\Models\Lab\LaporanKerusakan;
use App\Models\Lab\PcAssignment;
use App\Models\Lab\RequestSoftware;
use App\Models\Lab\SuratBebasLab;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pc_assignment'          => PcAssignment::where('is_active', true)->count(),
            'laporan_kerusakan_open' => LaporanKerusakan::whereIn('status', ['pending', 'open', 'in_progress'])->count(),
            'software_pending'       => RequestSoftware::where('status', 'pending')->count(),
            'kegiatan_today'         => Kegiatan::whereDate('tanggal', now())->count(),
            'surat_bebas_pending'    => SuratBebasLab::where('status', 'pending')->count(),
            'total_inventaris'       => Inventaris::count(),
        ];

        // Per Lab Stats
        $labs = Lab::withCount(['labInventaris', 'labTeams'])->get();

        $latest_laporan  = LaporanKerusakan::with(['inventaris', 'pelapor'])->latest()->take(5)->get();
        $latest_kegiatan = Kegiatan::with(['lab', 'penyelenggara'])->where('tanggal', '>=', now()->toDateString())->orderBy('tanggal')->take(5)->get();

        return view('pages.lab.dashboard.index', compact('stats', 'labs', 'latest_laporan', 'latest_kegiatan'));
    }
}
