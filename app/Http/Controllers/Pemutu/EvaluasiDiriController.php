<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use App\Models\Shared\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EvaluasiDiriController extends Controller
{
    public function index()
    {
        // User requested to show periods even if not in Tim Mutu yet.
        // Just show all available periods.

        $periodes = PeriodeSpmi::orderBy('periode', 'desc')
            ->paginate(12);

        return view('pages.pemutu.evaluasi-diri.index', compact('periodes'));
    }

    public function show($encryptedPeriodeId)
    {
        $periodeId = decryptId($encryptedPeriodeId);
        $periode   = PeriodeSpmi::findOrFail($periodeId);
        $user      = auth()->user();

        // Get User's Unit for this period
        $timMutu = TimMutu::where('periodespmi_id', $periodeId)
            ->where('pegawai_id', $user->pegawai?->pegawai_id)
            ->first();

        // FALLBACK: If not in tim mutu, just pick the first Unit found in the system
        // This is to "open" the feature for testing as requested.
        if ($timMutu) {
            $unit = $timMutu->orgUnit;
        } else {
            // Fallback to first unit
            $unit = StrukturOrganisasi::first();
        }

        return view('pages.pemutu.evaluasi-diri.show', compact('periode', 'unit'));
    }

    public function data($encryptedPeriodeId)
    {
        $periodeId = decryptId($encryptedPeriodeId);
        $user      = auth()->user();

        $timMutu = TimMutu::where('periodespmi_id', $periodeId)
            ->where('pegawai_id', $user->pegawai?->pegawai_id)
            ->first();

        if ($timMutu) {
            $unitId = $timMutu->org_unit_id;
        } else {
            // Fallback
            $unit   = StrukturOrganisasi::first();
            $unitId = $unit->orgunit_id ?? 0;
        }

        // Show ALL indicators, but eager load pivot for the current unit context
        $query = Indikator::with(['orgUnits' => function ($q) use ($unitId) {
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
                        data-url="' . route('pemutu.evaluasi-diri.edit', encryptId($row->indikator_id)) . '"
                        data-modal-title="Isi Evaluasi Diri">
                        Isi ED
                        </button>';
            })
            ->rawColumns(['indikator_full', 'target', 'capaian', 'file', 'action'])
            ->make(true);
    }

    public function edit($encryptedIndikatorId)
    {
        $indikatorId = decryptId($encryptedIndikatorId);
        $indikator   = Indikator::findOrFail($indikatorId);

        $user        = auth()->user();
        $userUnitIds = [];
        if ($user->pegawai) {
            $userUnitIds = TimMutu::where('pegawai_id', $user->pegawai->pegawai_id)->pluck('org_unit_id')->toArray();
        }

        // Try to find context unit. If user has units, use the first one.
        // If not, use system fallback (same as data method)
        if (! empty($userUnitIds)) {
            $targetUnitId = $userUnitIds[0];
        } else {
            $targetUnitId = StrukturOrganisasi::first()->orgunit_id ?? 0;
        }

        $pivot = DB::table('pemutu_indikator_orgunit')
            ->where('indikator_id', $indikatorId)
            ->where('org_unit_id', $targetUnitId)
            ->first();

        // Pass targetUnitId to view so update can use it if pivot is missing
        return view('pages.pemutu.evaluasi-diri.edit-ajax', compact('indikator', 'pivot', 'targetUnitId'));
    }

    public function update(Request $request, $encryptedIndikatorId)
    {
        $request->validate([
            'ed_capaian'     => 'required|string',
            'ed_analisis'    => 'required|string',
            'ed_attachment'  => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'target_unit_id' => 'nullable|integer',
        ]);

        $indikatorId = decryptId($encryptedIndikatorId);

        // Determine Unit ID (Priority: Request > User's Unit > Fallback)
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
            ->where('indikator_id', $indikatorId)
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
            // Create new pivot record if it doesn't exist
            $data['indikator_id'] = $indikatorId;
            $data['org_unit_id']  = $targetUnitId;
            $data['target']       = '-'; // Default if creating directly from ED
            $data['created_at']   = now();

            DB::table('pemutu_indikator_orgunit')->insert($data);
        }

        return response()->json(['status' => 'success', 'message' => 'Evaluasi Diri berhasil disimpan.', 'redirect' => null]);
    }
}
