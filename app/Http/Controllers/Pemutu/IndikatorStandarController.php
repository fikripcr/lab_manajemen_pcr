<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\IndikatorStandarRequest;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorPersonil;
use App\Models\Shared\Personil;
use Exception;
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

    public function store(IndikatorStandarRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $indikator = Indikator::create([
                    'indikator' => $request->indikator,
                    'target'    => $request->target,
                    'type'      => $request->type,
                    'parent_id' => $request->parent_id,
                ]);

                // Link to DokSub
                $indikator->dokSubs()->attach($request->doksub_id);
            });

            logActivity('pemutu', "Membuat indikator standar baru: {$request->indikator}");

            return jsonSuccess('Indikator Standar berhasil dibuat', route('pemutu.standar.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan indikator standar: ' . $e->getMessage());
        }
    }

    public function assign(Indikator $indikator)
    {
        $personils = Personil::orderBy('nama')->get();
        $assigned  = IndikatorPersonil::where('indikator_id', $indikator->indikator_id)
            ->with('personil')
            ->get();

        return view('pages.pemutu.standar.assign', compact('indikator', 'personils', 'assigned'));
    }

    public function storeAssignment(IndikatorStandarRequest $request, Indikator $indikator)
    {
        try {
            IndikatorPersonil::updateOrCreate(
                [
                    'indikator_id' => $indikator->indikator_id,
                    'personil_id'  => $request->personil_id,
                    'year'         => $request->year,
                    'semester'     => $request->semester,
                ],
                [
                    'target_value' => $request->target_value ?? $indikator->target,
                    'weight'       => $request->weight ?? 0,
                ]
            );

            logActivity('pemutu', "Menugaskan personel ke indikator ID: {$indikator->indikator_id}");

            return jsonSuccess('Penugasan Personel berhasil disimpan', route('pemutu.standar.assign', $indikator->indikator_id));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan penugasan: ' . $e->getMessage());
        }
    }

    public function destroyAssignment($id)
    {
        try {
            $assignment  = IndikatorPersonil::findOrFail($id);
            $indikatorId = $assignment->indikator_id;
            $assignment->delete();

            logActivity('pemutu', "Menghapus penugasan personel untuk indikator ID: {$indikatorId}");

            return jsonSuccess('Penugasan berhasil dihapus', route('pemutu.standar.assign', $indikatorId));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus penugasan: ' . $e->getMessage());
        }
    }
}
