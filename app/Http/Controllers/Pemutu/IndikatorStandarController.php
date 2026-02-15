<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorPersonil;
use App\Models\Pemutu\Personil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndikatorStandarController extends Controller
{
    public function index()
    {
        // Fetch indicators with type 'standar' or 'performa'
        $indikators = Indikator::whereIn('type', ['standar', 'performa'])
            ->with(['dokSubs', 'dokSubs.dokumen']) // Load related document info
            ->paginate(10);

        return view('pages.pemutu.standar.index', compact('indikators'));
    }

    public function create()
    {
        // Get Standard Documents only
        $dokumens = Dokumen::where('jenis', 'standar')->get();
        return view('pages.pemutu.standar.create', compact('dokumens'));
    }

    public function getDokSubs(Dokumen $dokumen)
    {
        return response()->json($dokumen->dokSubs()->orderBy('seq')->get(['doksub_id', 'judul']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doksub_id' => 'required|exists:pemutu_dok_sub,doksub_id',
            'indikator' => 'required|string',
            'target'    => 'required|string',
            'type'      => 'required|in:standar,performa',
            'parent_id' => 'nullable|exists:pemutu_indikator,indikator_id',
        ]);

        DB::transaction(function () use ($validated) {
            $indikator = Indikator::create([
                'indikator' => $validated['indikator'],
                'target'    => $validated['target'],
                'type'      => $validated['type'],
                'parent_id' => $validated['parent_id'],
            ]);

            // Link to DokSub
            $indikator->dokSubs()->attach($validated['doksub_id']);
        });

        return jsonSuccess('Indikator Standar berhasil dibuat', route('pemutu.standar.index'));
    }

    public function assign(Indikator $indikator)
    {
        $personils = Personil::orderBy('nama')->get();
        $assigned  = IndikatorPersonil::where('indikator_id', $indikator->indikator_id)
            ->with('personil')
            ->get();

        return view('pages.pemutu.standar.assign', compact('indikator', 'personils', 'assigned'));
    }

    public function storeAssignment(Request $request, Indikator $indikator)
    {
        $validated = $request->validate([
            'personil_id'  => 'required|exists:pemutu_personil,personil_id',
            'year'         => 'required|integer',
            'semester'     => 'required|integer',
            'target_value' => 'nullable|string',
            'weight'       => 'nullable|numeric|min:0',
        ]);

        IndikatorPersonil::updateOrCreate(
            [
                'indikator_id' => $indikator->indikator_id,
                'personil_id'  => $validated['personil_id'],
                'year'         => $validated['year'],
                'semester'     => $validated['semester'],
            ],
            [
                'target_value' => $validated['target_value'] ?? $indikator->target,
                'weight'       => $validated['weight'] ?? 0,
            ]
        );

        return jsonSuccess('Penugasan Personel berhasil disimpan', route('pemutu.standar.assign', $indikator->indikator_id));
    }

    public function destroyAssignment($id)
    {
        $assignment = IndikatorPersonil::findOrFail($id);
        $assignment->delete();

        return jsonSuccess('Penugasan berhasil dihapus', route('pemutu.standar.assign', $assignment->indikator_id));
    }
}
