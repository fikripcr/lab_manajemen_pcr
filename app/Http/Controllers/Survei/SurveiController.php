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
        $query = Survei::latest();
        return datatables()->of($query)
            ->addIndexColumn()
            ->editColumn('status', function ($s) {
                return $s->is_aktif
                    ? '<span class="badge bg-success text-white">Published</span>'
                    : '<span class="badge bg-secondary text-white">Draft</span>';
            })
            ->addColumn('pelaksanaan', function ($s) {
                $mulai   = $s->tanggal_mulai ? $s->tanggal_mulai->format('d/m/Y') : '-';
                $selesai = $s->tanggal_selesai ? $s->tanggal_selesai->format('d/m/Y') : '-';
                return "<div class='small text-muted'>$mulai s/d $selesai</div>";
            })
            ->addColumn('action', function ($s) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'       => route('survei.edit', $s->id),
                    'editModal'     => true,
                    'editTitle'     => 'Edit Pengaturan Survei',
                    'deleteUrl'     => route('survei.destroy', $s->id),
                    'deleteTitle'   => 'Hapus Survei?',
                    'customActions' => [
                        [
                            'label' => 'Form Builder',
                            'url'   => route('survei.builder', $s->id),
                            'icon'  => 'tool',
                            'class' => '',
                        ],
                        [
                            'label' => 'Lihat Jawaban',
                            'url'   => route('survei.responses', $s->id),
                            'icon'  => 'chart-bar',
                            'class' => '',
                        ],
                        [
                            'label'      => $s->is_aktif ? 'Jadikan Draft' : 'Publish Survei',
                            'url'        => '#',
                            'icon'       => $s->is_aktif ? 'eye-off' : 'eye',
                            'class'      => 'btn-toggle-status',
                            'attributes' => 'data-url="' . route('survei.toggle-status', $s->id) . '" data-title="' . ($s->is_aktif ? 'Jadikan Draft?' : 'Publish Survei?') . '"',
                        ],
                        [
                            'label'      => 'Salin Link',
                            'url'        => '#',
                            'icon'       => 'link',
                            'class'      => 'btn-copy-link',
                            'attributes' => 'data-link="' . route('survei.player', $s->slug) . '"',
                        ],
                        [
                            'label'      => 'Duplikasi',
                            'url'        => '#',
                            'icon'       => 'copy',
                            'class'      => 'btn-duplicate-single',
                            'attributes' => 'data-url="' . route('survei.duplicate', $s->id) . '"',
                        ],
                        [
                            'label' => 'Export CSV',
                            'url'   => route('survei.export', $s->id),
                            'icon'  => 'download',
                            'class' => '',
                        ],
                    ],
                ])->render();
            })
            ->rawColumns(['status', 'pelaksanaan', 'action'])
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

    public function duplicate(Survei $survei)
    {
        $newSurvei           = $survei->replicate();
        $newSurvei->judul    = $survei->judul . ' (Copy)';
        $newSurvei->slug     = Str::slug($newSurvei->judul) . '-' . Str::random(5);
        $newSurvei->is_aktif = false;
        $newSurvei->save();

        foreach ($survei->halaman as $halaman) {
            $newHalaman            = $halaman->replicate();
            $newHalaman->survei_id = $newSurvei->id;
            $newHalaman->save();

            foreach ($halaman->pertanyaan as $pertanyaan) {
                $newPertanyaan             = $pertanyaan->replicate();
                $newPertanyaan->survei_id  = $newSurvei->id;
                $newPertanyaan->halaman_id = $newHalaman->id;
                $newPertanyaan->save();

                foreach ($pertanyaan->opsi as $opsi) {
                    $newOpsi                = $opsi->replicate();
                    $newOpsi->pertanyaan_id = $newPertanyaan->id;
                    $newOpsi->save();
                }
            }
        }

        return jsonSuccess('Survei berhasil diduplikasi.');
    }

    public function export(Survei $survei)
    {
        $survei->load(['pengisian.jawaban.pertanyaan', 'pengisian.user']);

        $filename = "responses_" . Str::slug($survei->judul) . "_" . date('Ymd_His') . ".csv";
        $handle   = fopen('php://output', 'w');

        // Prepare headers
        $questions = $survei->pertanyaan()->orderBy('urutan')->get();
        $headers   = ['Timestamp', 'Nama', 'Username', 'Email'];
        foreach ($questions as $q) {
            $headers[] = $q->teks_pertanyaan;
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, $headers);

        foreach ($survei->pengisian as $p) {
            $row = [
                $p->created_at->format('Y-m-d H:i:s'),
                $p->user?->name ?? 'Guest',
                $p->user?->username ?? '-',
                $p->user?->email ?? '-',
            ];

            foreach ($questions as $q) {
                $jawaban = $p->jawaban->where('pertanyaan_id', $q->id)->first();
                if ($jawaban) {
                    if ($jawaban->opsi_id) {
                        $row[] = $jawaban->opsi->label ?? '-';
                    } elseif ($jawaban->jawaban_multiple) {
                        $labels = \App\Models\Survei\Opsi::whereIn('id', $jawaban->jawaban_multiple)->pluck('label')->toArray();
                        $row[]  = implode(', ', $labels);
                    } else {
                        $row[] = $jawaban->jawaban_teks ?? '-';
                    }
                } else {
                    $row[] = '-';
                }
            }
            fputcsv($handle, $row);
        }

        fclose($handle);
        exit;
    }

    public function destroy(Survei $survei)
    {
        $survei->delete();
        return jsonSuccess('Survei berhasil dihapus.');
    }
}
