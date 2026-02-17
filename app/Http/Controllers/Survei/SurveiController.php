<?php
namespace App\Http\Controllers\Survei;

use App\Http\Controllers\Controller;
use App\Models\Survei\Survei;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SurveiController extends Controller
{
    public function index()
    {
        return view('pages.survei.admin.index');
    }

    public function paginate()
    {
        $query = Survei::query();
        return datatables()->of($query)
            ->addIndexColumn()
            ->editColumn('tanggal_mulai', fn($s) => $s->tanggal_mulai ? $s->tanggal_mulai->format('d M Y H:i') : '-')
            ->editColumn('tanggal_selesai', fn($s) => $s->tanggal_selesai ? $s->tanggal_selesai->format('d M Y H:i') : '-')
            ->editColumn('status', function ($s) {
                return $s->is_aktif
                    ? '<span class="badge bg-success text-white">Aktif</span>'
                    : '<span class="badge bg-secondary text-white">Tidak Aktif</span>';
            })
            ->addColumn('action', function ($s) {
                return view('pages.survei.admin._actions', compact('s'));
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.survei.admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'target_role'     => 'required|in:Mahasiswa,Dosen,Tendik,Alumni,Umum',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_aktif'        => 'boolean',
            'wajib_login'     => 'boolean',
            'bisa_isi_ulang'  => 'boolean',
            'mode'            => 'required|in:Linear,Bercabang',
        ]);

        $validated['slug'] = Str::slug($validated['judul']) . '-' . Str::random(5);

        $survei = Survei::create($validated);

        // Auto-create first page
        $survei->halaman()->create([
            'judul_halaman' => 'Halaman 1',
            'urutan'        => 1,
        ]);

        return jsonSuccess('Survei berhasil dibuat.', route('survei.builder', $survei->id));
    }

    public function edit(Survei $survei)
    {
        return view('pages.survei.admin.edit', compact('survei'));
    }

    public function update(Request $request, Survei $survei)
    {
        $validated = $request->validate([
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'target_role'     => 'required|in:Mahasiswa,Dosen,Tendik,Alumni,Umum',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_aktif'        => 'boolean',
            'wajib_login'     => 'boolean',
            'bisa_isi_ulang'  => 'boolean',
            'mode'            => 'required|in:Linear,Bercabang',
        ]);

        $survei->update($validated);

        return jsonSuccess('Survei berhasil diperbarui.');
    }

    public function responses(Survei $survei)
    {
        $survei->load(['halaman.pertanyaan', 'pengisian' => function ($q) {
            $q->with(['user', 'jawaban.pertanyaan', 'jawaban.opsi'])->latest();
        }]);
        return view('pages.survei.admin.responses', compact('survei'));
    }

    public function toggleStatus(Survei $survei)
    {
        $survei->update(['is_aktif' => ! $survei->is_aktif]);
        $status = $survei->is_aktif ? 'dipublikasikan' : 'di-unpublish';
        return jsonSuccess("Survei berhasil {$status}.");
    }

    public function destroy(Survei $survei)
    {
        $survei->delete();
        return jsonSuccess('Survei berhasil dihapus.');
    }
}
