<?php
namespace App\Http\Controllers\Survei;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survei\SurveiRequest;
use App\Models\Survei\Survei;
use App\Services\Survei\SurveiService;
use Illuminate\Support\Str;

class SurveiController extends Controller
{
    public function __construct(protected SurveiService $surveiService)
    {}

    public function index()
    {
        return view('pages.survei.admin.index');
    }

    public function data()
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
                    'editUrl'       => route('survei.edit', $s->encrypted_survei_id),
                    'editModal'     => true,
                    'editTitle'     => 'Edit Pengaturan Survei',
                    'deleteUrl'     => route('survei.destroy', $s->encrypted_survei_id),
                    'deleteTitle'   => 'Hapus Survei?',
                    'customActions' => [
                        [
                            'label' => 'Form Builder',
                            'url'   => route('survei.builder', $s->encrypted_survei_id),
                            'icon'  => 'tool',
                            'class' => '',
                        ],
                        [
                            'label' => 'Lihat Jawaban',
                            'url'   => route('survei.responses', $s->encrypted_survei_id),
                            'icon'  => 'chart-bar',
                            'class' => '',
                        ],
                        [
                            'label'      => $s->is_aktif ? 'Jadikan Draft' : 'Publish Survei',
                            'url'        => '#',
                            'icon'       => $s->is_aktif ? 'eye-off' : 'eye',
                            'class'      => 'btn-toggle-status',
                            'attributes' => 'data-url="' . route('survei.toggle-status', $s->encrypted_survei_id) . '" data-title="' . ($s->is_aktif ? 'Jadikan Draft?' : 'Publish Survei?') . '"',
                        ],
                        [
                            'label'      => 'Salin Link',
                            'url'        => '#',
                            'icon'       => 'link',
                            'class'      => 'btn-copy-link',
                            'attributes' => 'data-link="' . route('survei.public.show', $s->slug) . '"',
                        ],
                        [
                            'label'      => 'Duplikasi',
                            'url'        => '#',
                            'icon'       => 'copy',
                            'class'      => 'btn-duplicate-single',
                            'attributes' => 'data-url="' . route('survei.duplicate', $s->encrypted_survei_id) . '"',
                        ],
                        [
                            'label' => 'Export CSV',
                            'url'   => route('survei.export', $s->encrypted_survei_id),
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
        $survei = new Survei();
        return view('pages.survei.admin.create-edit-ajax', compact('survei'));
    }

    public function store(SurveiRequest $request)
    {
        $survei = $this->surveiService->createSurvei($request->validated());
        return jsonSuccess('Survei berhasil dibuat.', route('survei.builder', $survei->encrypted_survei_id));
    }

    public function edit(Survei $survei)
    {
        return view('pages.survei.admin.create-edit-ajax', compact('survei'));
    }

    public function update(SurveiRequest $request, Survei $survei)
    {
        $this->surveiService->updateSurvei($survei, $request->validated());
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
        $this->surveiService->toggleStatus($survei);
        $status = $survei->is_aktif ? 'dipublikasikan' : 'di-unpublish';
        return jsonSuccess("Survei berhasil {$status}.");
    }

    public function duplicate(Survei $survei)
    {
        $this->surveiService->duplicateSurvei($survei);
        return jsonSuccess('Survei berhasil diduplikasi.');
    }

    public function export(Survei $survei)
    {
        $survei = $this->surveiService->getResponsesForExport($survei);

        $filename = "responses_" . Str::slug($survei->judul) . "_" . date('Ymd_His') . ".csv";
        $handle   = fopen('php://output', 'w');

        // Prepare headers (using pre-eager-loaded relation)
        $questions = $survei->pertanyaan;
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
                $jawaban = $p->jawaban->where('pertanyaan_id', $q->pertanyaan_id)->first();
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
        $this->surveiService->deleteSurvei($survei);
        return jsonSuccess('Survei berhasil dihapus.');
    }
}
