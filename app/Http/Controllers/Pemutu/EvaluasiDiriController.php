<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use App\Models\Shared\StrukturOrganisasi;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EvaluasiDiriController extends Controller
{
    public function index()
    {
        $pageTitle = 'Evaluasi Diri';
        $periodes  = PeriodeSpmi::orderBy('periode', 'desc')->paginate(12);

        return view('pages.pemutu.evaluasi-diri.index', compact('pageTitle', 'periodes'));
    }

    public function show(PeriodeSpmi $periode)
    {
        try {
            $user = auth()->user();

            // Get User's Unit for this period
            $timMutu = TimMutu::where('periodespmi_id', $periode->periodespmi_id)
                ->where('pegawai_id', $user->pegawai?->pegawai_id)
                ->first();

            // Fallback for testing/administration
            if ($timMutu) {
                $unit = $timMutu->orgUnit;
            } else {
                $unit = StrukturOrganisasi::first();
            }

            return view('pages.pemutu.evaluasi-diri.show', compact('periode', 'unit'));
        } catch (Exception $e) {
            abort(404);
        }
    }

    public function data(PeriodeSpmi $periode)
    {
        try {
            $user = auth()->user();

            $timMutu = TimMutu::where('periodespmi_id', $periode->periodespmi_id)
                ->where('pegawai_id', $user->pegawai?->pegawai_id)
                ->first();

            if ($timMutu) {
                $unitId = $timMutu->org_unit_id;
            } else {
                $unit   = StrukturOrganisasi::first();
                $unitId = $unit->orgunit_id ?? 0;
            }

            $query = Indikator::whereHas('dokSubs.dokumen', function ($q) use ($periode) {
                $q->where('periode', $periode->periode);
            })->with(['orgUnits' => function ($q) use ($unitId) {
                $q->where('pemutu_indikator_orgunit.org_unit_id', $unitId);
            }]);

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
                    return $row->orgUnits->first()->pivot->ed_analisis ?? '-';
                })
                ->addColumn('file', function ($row) {
                    $file = $row->orgUnits->first()->pivot->ed_attachment ?? null;
                    if ($file) {
                        $url = Storage::url($file);
                        return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-ghost-primary"><i class="ti ti-file-download"></i> Unduh</a>';
                    }
                    return '-';
                })
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="btn btn-sm btn-primary ajax-modal-btn"
                        data-url="' . route('pemutu.evaluasi-diri.edit', $row->encrypted_indikator_id) . '"
                        data-modal-title="Isi Evaluasi Diri">
                        Isi ED
                        </button>';
                })
                ->rawColumns(['indikator_full', 'target', 'capaian', 'file', 'action'])
                ->make(true);
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function edit(Indikator $indikator)
    {
        try {
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

            return view('pages.pemutu.evaluasi-diri.edit-ajax', compact('indikator', 'pivot', 'targetUnitId'));
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function update(\App\Http\Requests\Pemutu\EvaluasiDiriRequest $request, Indikator $indikator)
    {
        try {
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
                'updated_at'  => now(),
            ];

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
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan Evaluasi Diri: ' . $e->getMessage());
        }
    }
}
