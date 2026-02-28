<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\EvaluasiDiriRequest;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use App\Models\Shared\StrukturOrganisasi;
use App\Services\Pemutu\IndikatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EvaluasiDiriController extends Controller
{
    public function index()
    {
        $pageTitle = 'Evaluasi Diri';
        $periodes  = PeriodeSpmi::orderBy('periode', 'desc')->paginate(12);

        // Global counts (not per-period, as orgunit pivot has no period FK)
        $edTotal  = DB::table('pemutu_indikator_orgunit')->count();
        $edFilled = DB::table('pemutu_indikator_orgunit')->whereNotNull('ed_capaian')->where('ed_capaian', '!=', '')->count();

        return view('pages.pemutu.evaluasi-diri.index', compact('pageTitle', 'periodes', 'edTotal', 'edFilled'));
    }

    public function show(PeriodeSpmi $periode)
    {
        $user = auth()->user();

        // Get User's Units for this period
        $timMutuUnits = TimMutu::with('orgUnit')
            ->where('periodespmi_id', $periode->periodespmi_id)
            ->where('pegawai_id', $user->pegawai?->pegawai_id)
            ->get()
            ->pluck('orgUnit')
            ->filter();

        // Fallback for testing/administration
        // Ambil daftar seluruh unit untuk filter (atau biarkan TimMutu sementara waktu sesuai role)
        $userUnits = TimMutu::where('periodespmi_id', $periode->periodespmi_id)
            ->with('orgUnit') // Ambil semua unit di periode ini, bukan cuma unit pegawai yang login
            ->get()
            ->pluck('orgUnit')
            ->filter();

        $selectedUnitId = request('unit_id');
        // Supaya tampilan table render (tidak masuk ke block empty state)
        $unit = true;

        return view('pages.pemutu.evaluasi-diri.show', compact('periode', 'unit', 'userUnits', 'selectedUnitId'));
    }

    public function data(Request $request, PeriodeSpmi $periode)
    {
        $unitId = $request->input('unit_id') ? (int) $request->input('unit_id') : null;

        // Tampilkan semua indikator sesuai unit dan periode yang dipilih
        $filters = [
            'kelompok_indikator' => $periode->jenis,
            'tahun_dokumen'      => $periode->tahun,
        ];
        $query = app(IndikatorService::class)->getByOrgUnit($unitId, $filters);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('indikator_full', function ($row) {
                return '<strong>' . ($row->no_indikator ?? '-') . '</strong><br>' . $row->indikator;
            })
            ->addColumn('target', function ($row) {
                return $row->orgUnits->first()->pivot->target ?? '<span class="text-muted">-</span>';
            })
            ->addColumn('capaian', function ($row) {
                return $row->orgUnits->first()->pivot->ed_capaian ?? '<span class="text-muted fst-italic">Belum diisi</span>';
            })
            ->addColumn('analisis', function ($row) {
                $pivot = $row->orgUnits->first()->pivot ?? null;
                $text  = $pivot->ed_analisis ?? '-';
                $html  = '<div style="max-height: 200px; overflow-y: auto;" class="mb-2">' . nl2br(e($text)) . '</div>';

                // Evidence items
                $evidenceHtml = '';
                if ($pivot) {
                    $file = $pivot->ed_attachment;
                    if ($file) {
                        // Encrypt id from pivot
                        $url           = route('pemutu.evaluasi-diri.download', encryptId($pivot->indikorgunit_id));
                        $evidenceHtml .= '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-ghost-primary me-1 mb-1" title="Unduh File Pendukung" data-bs-toggle="tooltip"><i class="ti ti-file-download fs-3"></i></a>';
                    }

                    if (! empty($pivot->ed_links)) {
                        $links = json_decode($pivot->ed_links, true) ?? [];
                        if (is_array($links)) {
                            foreach ($links as $link) {
                                $name          = htmlspecialchars($link['name'] ?? 'Tautan');
                                $url           = htmlspecialchars($link['url'] ?? '#');
                                $evidenceHtml .= '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-ghost-info me-1 mb-1" title="' . $name . '" data-bs-toggle="tooltip"><i class="ti ti-link fs-3"></i></a>';
                            }
                        }
                    }
                }

                if ($evidenceHtml) {
                    $html .= '<div class="d-flex flex-wrap border-top pt-2">' . $evidenceHtml . '</div>';
                }

                return $html;
            })
            ->addColumn('action', function ($row) {
                return '<button type="button" class="btn btn-sm btn-primary ajax-modal-btn"
                    data-url="' . route('pemutu.evaluasi-diri.edit', $row->encrypted_indikator_id) . '"
                    data-modal-title="Isi Evaluasi Diri">
                    Isi ED
                    </button>';
            })
            ->rawColumns(['indikator_full', 'target', 'capaian', 'file', 'action', 'analisis'])
            ->make(true);
    }

    public function edit(Indikator $indikator)
    {
        $user = auth()->user();

        $userUnitIds = [];
        if ($user->pegawai) {
            $userUnitIds = TimMutu::where('pegawai_id', $user->pegawai->pegawai_id)->pluck('org_unit_id')->toArray();
        }

        if (! empty($userUnitIds)) {
            $targetUnitId = $userUnitIds[0];
        } else {
            $targetUnitId = StrukturOrganisasi::first()->orgunit_id ?? 0;
        }

        $pivot = DB::table('pemutu_indikator_orgunit')
            ->where('indikator_id', $indikator->indikator_id)
            ->where('org_unit_id', $targetUnitId)
            ->first();

        $breadcrumbs = [];
        $current     = $indikator;
        while ($current) {
            array_unshift($breadcrumbs, compact('current'));
            $current = $current->parent;
        }

        // Get Induk Dokumen Tree
        $indukDokumenTree = [];
        $firstDokSub      = $indikator->dokSubs()->with('dokumen')->first();

        if (! $firstDokSub) {
            $parent = $indikator->parent;
            while ($parent && ! $firstDokSub) {
                $firstDokSub = $parent->dokSubs()->with('dokumen')->first();
                $parent      = $parent->parent;
            }
        }

        if ($firstDokSub) {
            array_unshift($indukDokumenTree, [
                'judul' => $firstDokSub->judul,
                'kode'  => '',
                'type'  => 'dok_sub',
            ]);
            $currDok = $firstDokSub->dokumen;
            while ($currDok) {
                array_unshift($indukDokumenTree, [
                    'judul' => $currDok->judul,
                    'kode'  => $currDok->kode,
                    'type'  => 'dokumen',
                ]);
                $currDok = $currDok->parent;
            }
        }

        $edLinks = [];
        if ($pivot && ! empty($pivot->ed_links)) {
            $edLinks = json_decode($pivot->ed_links, true) ?? [];
        }

        return view('pages.pemutu.evaluasi-diri.edit-ajax', compact('indikator', 'pivot', 'targetUnitId', 'breadcrumbs', 'edLinks', 'indukDokumenTree'));
    }

    public function update(EvaluasiDiriRequest $request, Indikator $indikator)
    {
        $validated = $request->validated();

        if ($request->filled('target_unit_id')) {
            $targetUnitId = $request->target_unit_id;
        } else {
            $user = auth()->user();
            if ($user->pegawai && $timMutu = TimMutu::where('pegawai_id', $user->pegawai->pegawai_id)->first()) {
                $targetUnitId = $timMutu->org_unit_id;
            } else {
                $targetUnitId = StrukturOrganisasi::first()->orgunit_id ?? 0;
            }
        }

        $pivot = DB::table('pemutu_indikator_orgunit')
            ->where('indikator_id', $indikator->indikator_id)
            ->where('org_unit_id', $targetUnitId)
            ->first();

        $data = [
            'ed_capaian'  => $request->ed_capaian,
            'ed_analisis' => $request->ed_analisis,
            'ed_skala'    => $request->filled('ed_skala') ? (int) $request->ed_skala : null,
            'updated_at'  => now(),
        ];


        // Handle Links JSON
        $linksArray = [];
        if ($request->has('ed_links_name') && is_array($request->ed_links_name)) {
            $names = $request->ed_links_name;
            $urls  = $request->ed_links_url ?? [];
            foreach ($names as $index => $name) {
                $url = $urls[$index] ?? null;
                if (! empty($name) && ! empty($url)) {
                    $linksArray[] = [
                        'name' => $name,
                        'url'  => $url,
                    ];
                }
            }
        }
        $data['ed_links'] = ! empty($linksArray) ? json_encode($linksArray) : null;

        if ($request->hasFile('ed_attachment')) {
            if ($pivot && $pivot->ed_attachment && Storage::exists($pivot->ed_attachment)) {
                Storage::delete($pivot->ed_attachment);
            }
            $path                  = $request->file('ed_attachment')->store('public/pemutu/ed-attachments');
            $data['ed_attachment'] = $path;
        }

        if ($pivot) {
            DB::table('pemutu_indikator_orgunit')
                ->where('indikorgunit_id', $pivot->indikorgunit_id)
                ->update($data);
        } else {
            $data['indikator_id'] = $indikator->indikator_id;
            $data['org_unit_id']  = $targetUnitId;
            $data['target']       = '-';
            $data['created_at']   = now();
            DB::table('pemutu_indikator_orgunit')->insert($data);
        }

        logActivity('pemutu', "Mengisi Evaluasi Diri untuk indikator ID: {$indikator->indikator_id}");

        return jsonSuccess('Evaluasi Diri berhasil disimpan.');
    }

    public function downloadAttachment($id)
    {
        $realId = decryptIdIfEncrypted($id);
        
        $pivot = DB::table('pemutu_indikator_orgunit')
            ->where('indikorgunit_id', $realId)
            ->first();

        return downloadStorageFile($pivot->ed_attachment ?? null, logActivity: true);
    }
}
