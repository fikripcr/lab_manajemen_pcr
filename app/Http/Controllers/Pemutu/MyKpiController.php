<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\IndikatorPegawai;
use App\Models\Shared\Personil;
use Illuminate\Support\Facades\Auth;

class MyKpiController extends Controller
{
    public function index()
    {
        // For now, assuming User is linked to Personil via email or some other mechanism.
        // If not, we might need to find the personil_id based on Auth::user()->email

        $user     = Auth::user();
        $personil = Personil::find($user->pegawai_id);

        if (! $personil) {
            return view('pages.pemutu.mykpi.index', ['kpis' => collect([]), 'error' => 'No Personil data found for your account.']);
        }

        $kpis = IndikatorPegawai::where('pegawai_id', $personil->personil_id)
            ->with(['indikator'])
            ->orderBy('year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return view('pages.pemutu.mykpi.index', compact('kpis', 'personil'));
    }

    public function edit(IndikatorPegawai $kpi)
    {
        try {
            $user     = Auth::user();
            $personil = Personil::find($user->pegawai_id);

            if ($kpi->personil_id !== $personil?->personil_id) {
                abort(403);
            }

            return view('pages.pemutu.mykpi.edit', compact('kpi'));
        } catch (Exception $e) {
            abort(404);
        }
    }

    public function update(\App\Http\Requests\Pemutu\MyKpiRequest $request, IndikatorPegawai $kpi)
    {
        try {
            $user     = Auth::user();
            $personil = \App\Models\Shared\Personil::find($user->pegawai_id);

            if ($kpi->personil_id !== $personil?->personil_id) {
                abort(403);
            }

            $validated = $request->validated();

            if ($request->hasFile('attachment')) {
                // Delete old attachment if exists
                if ($kpi->attachment && \Illuminate\Support\Facades\Storage::exists($kpi->attachment)) {
                    \Illuminate\Support\Facades\Storage::delete($kpi->attachment);
                }
                $path                    = $request->file('attachment')->store('pemutu/kpi_evidence', 'public');
                $validated['attachment'] = $path;
            }

            $validated['status'] = 'submitted'; // Auto-submit on save for now

            $kpi->update($validated);

            logActivity('pemutu', "Memperbarui KPI pribadi: " . ($kpi->indikator?->indikator ?? $kpi->id));

            return jsonSuccess('KPI berhasil diperbarui', route('pemutu.mykpi.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui KPI: ' . $e->getMessage());
        }
    }
}
