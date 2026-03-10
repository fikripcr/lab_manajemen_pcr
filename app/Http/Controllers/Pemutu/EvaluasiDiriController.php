<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\EvaluasiDiriRequest;
use App\Http\Requests\Pemutu\PtpRequest;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use App\Models\Shared\StrukturOrganisasi;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EvaluasiDiriController extends Controller
{
    public function __construct(
        protected PeriodeSpmiService $PeriodeSpmiService,
        protected IndikatorService $IndikatorService,
    ) {}

    public function index()
    {
        $pageTitle = 'Evaluasi Diri';
        $periodes  = $this->PeriodeSpmiService->getPeriodes();

        // Global counts (not per-period, as orgunit pivot has no period FK)
        $edTotal  = DB::table('pemutu_indikator_orgunit')->count();
        $edFilled = DB::table('pemutu_indikator_orgunit')->whereNotNull('ed_capaian')->where('ed_capaian', '!=', '')->count();

        return view('pages.pemutu.evaluasi-diri.index', compact('pageTitle', 'periodes', 'edTotal', 'edFilled'));
    }

    public function show(PeriodeSpmi $periode)
    {
        // Cek jadwal ED sudah diatur
        $jadwalTersedia = $periode->ed_awal && $periode->ed_akhir;
        if (! $jadwalTersedia) {
            return view('pages.pemutu.evaluasi-diri.show', compact('periode', 'jadwalTersedia'));
        }

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

        return view('pages.pemutu.evaluasi-diri.show', compact('periode', 'unit', 'userUnits', 'selectedUnitId', 'jadwalTersedia'));
    }

    public function data(Request $request, PeriodeSpmi $periode)
    {
        $unitId = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;

        $query = $this->IndikatorService->getUnifiedSpmiQuery($periode, $unitId);

        return DataTables::of($query)
            ->addColumn('no', function ($row) {
                return pemutuDtColNo($row);
            })
            ->addColumn('indikator_full', function ($row) {
                return pemutuDtColIndikator($row);
            })
            ->addColumn('target', function ($row) {
                return pemutuDtColTarget($row);
            })
            ->addColumn('capaian', function ($row) {
                return $row->orgUnits->first()->pivot->ed_capaian ?? '<span class="text-muted fst-italic">Belum diisi</span>';
            })
            ->addColumn('analisis', function ($row) {
                return pemutuDtColAnalisisEd($row);
            })
            ->addColumn('action', function ($row) use ($unitId) {
                // If a specific unit is filtered, pass it. Otherwise try to get it from the pivot.
                $targetUnit = $unitId ?? ($row->orgUnits->first()->pivot->org_unit_id ?? '');
                $url        = route('pemutu.evaluasi-diri.edit', $row->encrypted_indikator_id);
                if ($targetUnit) {
                    $url .= '?unit_id=' . $targetUnit;
                }

                return '<button type="button" class="btn btn-sm btn-primary ajax-modal-btn"
                    data-url="' . $url . '"
                    data-modal-title="Isi Evaluasi Diri"
                    data-modal-size="modal-xl">
                    Isi
                    </button>';
            })
            ->rawColumns(['no', 'indikator_full', 'target', 'capaian', 'analisis', 'action'])
            ->make(true);
    }

    public function edit(Request $request, Indikator $indikator)
    {
        $user = auth()->user();

        if ($request->filled('unit_id')) {
            $targetUnitId = decryptIdIfEncrypted($request->input('unit_id'));
        } else {
            $userUnitIds = [];
            if ($user->pegawai) {
                $userUnitIds = TimMutu::where('pegawai_id', $user->pegawai->pegawai_id)->pluck('org_unit_id')->toArray();
            }

            if (! empty($userUnitIds)) {
                $targetUnitId = $userUnitIds[0];
            } else {
                $targetUnitId = StrukturOrganisasi::first()->orgunit_id ?? 0;
            }
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
            $targetUnitId = decryptIdIfEncrypted($request->target_unit_id);
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

    /**
     * Data untuk tab Pelaksanaan Perbaikan (KTS Tahun Lalu).
     */
    public function ptpData(Request $request, PeriodeSpmi $periode)
    {
        $unitId = $request->input('unit_id') ? decryptIdIfEncrypted($request->input('unit_id')) : null;

        // Cari periode tahun lalu dengan jenis yang sama
        $prevYear   = (int) $periode->periode - 1;
        $prevPeriod = PeriodeSpmi::where('periode', $prevYear)
            ->where('jenis_periode', $periode->jenis_periode)
            ->first();

        if (! $prevPeriod) {
            return DataTables::of(collect([]))->make(true);
        }

        // Ambil indikator KTS dari periode tahun lalu
        $query = $this->IndikatorService->getUnifiedSpmiQuery($prevPeriod, $unitId, [
            'ami_hasil_akhir' => 0, // KTS
        ]);

        return DataTables::of($query)
            ->addColumn('no', function ($row) {
                return pemutuDtColNo($row);
            })
            ->addColumn('indikator_full', function ($row) {
                return pemutuDtColIndikator($row);
            })
            ->addColumn('rtp_isi', function ($row) {
                return $row->orgUnits->first()->pivot->ami_rtp_isi ?? '<span class="text-muted fst-italic">Tidak ada RTP</span>';
            })
            ->addColumn('ptp_isi', function ($row) {
                return $row->orgUnits->first()->pivot->ed_ptp_isi ?? '<span class="text-muted fst-italic">Belum diisi</span>';
            })
            ->addColumn('action', function ($row) {
                $indOrgId = $row->orgUnits->first()->pivot->indikorgunit_id;

                return '<button type="button" class="btn btn-sm btn-warning ajax-modal-btn"
                    data-url="' . route('pemutu.evaluasi-diri.ptp-edit', encryptId($indOrgId)) . '"
                    data-modal-title="Isi Pelaksanaan Tindakan Perbaikan (PTP)"
                    data-modal-size="modal-lg">
                    Isi PTP
                </button>';
            })
            ->rawColumns(['no', 'indikator_full', 'rtp_isi', 'ptp_isi', 'action'])
            ->make(true);
    }

    /**
     * Modal Form: Isi Pelaksanaan Tindakan Perbaikan (PTP).
     */
    public function editPtp(IndikatorOrgUnit $indOrg)
    {
        return view('pages.pemutu.evaluasi-diri._ptp_form', compact('indOrg'));
    }

    /**
     * Simpan Pelaksanaan Tindakan Perbaikan (PTP).
     */
    public function updatePtp(PtpRequest $request, IndikatorOrgUnit $indOrg)
    {
        $indOrg->update([
            'ed_ptp_isi' => $request->ed_ptp_isi,
        ]);

        logActivity('pemutu', "Mengisi Pelaksanaan Tindakan Perbaikan (PTP) untuk indikorgunit ID: {$indOrg->indikorgunit_id}");

        return jsonSuccess('Pelaksanaan Tindakan Perbaikan (PTP) berhasil disimpan.');
    }
}
