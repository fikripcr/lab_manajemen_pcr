<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lab\MataKuliah;
use App\Models\Lab\Pengumuman;
use App\Models\Lab\RequestSoftware;
use App\Models\Shared\Slideshow;
use App\Http\Requests\Public\PublicSoftwareRequest;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        // Get the 6 most recent published announcements/news
        $recentNews = Pengumuman::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Get approved software requests
        $approvedSoftwareRequests = RequestSoftware::where('status', 'disetujui')
            ->with(['mataKuliahs'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get active slideshows
        $slideshows = Slideshow::where('is_active', true)
            ->orderBy('seq', 'asc')
            ->get();

        // Get active FAQs grouped by category
        $faqs = \App\Models\Shared\FAQ::where('is_active', true)
            ->orderBy('seq', 'asc')
            ->get()
            ->groupBy('category');

        return view('pages.public.home', compact('recentNews', 'approvedSoftwareRequests', 'slideshows', 'faqs'));
    }

    public function showNews(Pengumuman $pengumuman)
    {
        // Only show published items
        if (! $pengumuman->is_published) {
            abort(404);
        }

        return view('pages.public.news.show', compact('pengumuman'));
    }

    public function requestSoftware()
    {
        $mataKuliahs = MataKuliah::all(); // For old values display
        return view('pages.public.request-software', compact('mataKuliahs'));
    }

    public function storeSoftwareRequest(PublicSoftwareRequest $request)
    {
        $validated = $request->validated();

        $softwareRequest = RequestSoftware::create([
            'nama_software' => $validated['nama_software'],
            'alasan'        => $validated['alasan'],
            'status'        => 'Pending', // waiting for approval
        ]);

        // Attach selected mata kuliah if provided
        if (isset($validated['mata_kuliah_ids'])) {
            $softwareRequest->mataKuliahs()->attach($validated['mata_kuliah_ids']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Permintaan software berhasil dikirim dan sedang menunggu persetujuan.',
            ]);
        }

        return redirect()->route('public.request-software')->with('success', 'Permintaan software berhasil dikirim dan sedang menunggu persetujuan.');
    }

    public function getMataKuliah(Request $request)
    {
        $search = $request->get('q');

        $mataKuliahs = MataKuliah::when($search, function ($query, $search) {
            return $query->where('kode_mk', 'LIKE', "%{$search}%")
                ->orWhere('nama_mk', 'LIKE', "%{$search}%");
        })
            ->select('mata_kuliah_id', 'kode_mk', 'nama_mk')
            ->get()
            ->map(function ($mk) {
                return [
                    'id'   => $mk->mata_kuliah_idid,
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

        return view('pages.public.news.index', compact('allNews'));
    }
}
