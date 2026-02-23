<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\MyKpiRequest;
use App\Models\Pemutu\IndikatorPegawai;
use Exception;
use Illuminate\Support\Facades\Storage;

class MyKpiController extends Controller
{
    public function index()
    {
        $kpis = IndikatorPegawai::with(['indikator', 'pegawai'])
            ->orderBy('year', 'desc')
            ->get();

        return view('pages.pemutu.mykpi.index', ['kpis' => $kpis, 'personil' => null]);
    }

    public function edit(IndikatorPegawai $kpi)
    {
        try {
            return view('pages.pemutu.mykpi.edit', compact('kpi'));
        } catch (Exception $e) {
            abort(404);
        }
    }

    public function update(MyKpiRequest $request, IndikatorPegawai $kpi)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('attachment')) {
                // Delete old attachment if exists
                if ($kpi->attachment && Storage::exists($kpi->attachment)) {
                    Storage::delete($kpi->attachment);
                }
                $path                    = $request->file('attachment')->store('pemutu/kpi_evidence', 'public');
                $validated['attachment'] = $path;
            }

            $validated['status'] = 'submitted'; // Auto-submit on save for now

            $kpi->update($validated);

            logActivity('pemutu', "Memperbarui KPI pribadi: " . ($kpi->indikator?->indikator ?? $kpi->indikator_pegawai_id));

            return jsonSuccess('KPI berhasil diperbarui', route('pemutu.mykpi.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui KPI: ' . $e->getMessage());
        }
    }
}
