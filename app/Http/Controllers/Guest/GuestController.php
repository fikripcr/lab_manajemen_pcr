<?php
namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Lab\MataKuliah;
use App\Models\Lab\Pengumuman;
use App\Models\Lab\RequestSoftware;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function home()
    {
        // Get the 6 most recent published announcements/news
        $recentNews = Pengumuman::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get approved software requests
        $approvedSoftwareRequests = RequestSoftware::where('status', 'disetujui')
            ->with(['mataKuliahs'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('pages.guest.home', compact('recentNews', 'approvedSoftwareRequests'));
    }

    public function showNews($id)
    {
        $realId = decryptId($id);
        if (! $realId) {
            abort(404);
        }

        $pengumuman = Pengumuman::findOrFail($realId);

        // Only show published items
        if (! $pengumuman->is_published) {
            abort(404);
        }

        return view('pages.guest.news.show', compact('pengumuman'));
    }

    public function requestSoftware()
    {
        $mataKuliahs = MataKuliah::all(); // For old values display
        return view('pages.guest.request-software', compact('mataKuliahs'));
    }

    public function storeSoftwareRequest(Request $request)
    {
        $request->validate([
            'nama_software'     => 'required|string|max:255',
            'alasan'            => 'required|string',
            'mata_kuliah_ids'   => 'array',
            'mata_kuliah_ids.*' => 'exists:lab_mata_kuliahs,mata_kuliah_id',
        ]);

        $softwareRequest = RequestSoftware::create([
            'nama_software' => $request->nama_software,
            'alasan'        => $request->alasan,
            'status'        => 'Pending', // waiting for approval
        ]);

        // Attach selected mata kuliah if provided
        if ($request->mata_kuliah_ids) {
            $softwareRequest->mataKuliahs()->attach($request->mata_kuliah_ids);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Permintaan software berhasil dikirim dan sedang menunggu persetujuan.',
            ]);
        }

        return redirect()->route('guest.request-software')->with('success', 'Permintaan software berhasil dikirim dan sedang menunggu persetujuan.');
    }

    public function getMataKuliah(Request $request)
    {
        $search = $request->get('q');

        $mataKuliahs = MataKuliah::when($search, function ($query, $search) {
            return $query->where('kode_mk', 'LIKE', "%{$search}%")
                ->orWhere('nama_mk', 'LIKE', "%{$search}%");
        })
            ->select('id', 'kode_mk', 'nama_mk')
            ->get()
            ->map(function ($mk) {
                return [
                    'id'   => $mk->id,
                    'text' => $mk->kode_mk . ' - ' . $mk->nama_mk,
                ];
            });

        return response()->json([
            'results' => $mataKuliahs,
        ]);
    }

    public function showAllNews()
    {
        $allNews = Pengumuman::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate to show 10 per page

        return view('pages.guest.news.index', compact('allNews'));
    }
}
