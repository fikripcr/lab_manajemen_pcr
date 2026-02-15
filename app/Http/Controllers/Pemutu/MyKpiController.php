<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\IndikatorPersonil;
use App\Models\Pemutu\Personil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyKpiController extends Controller
{
    public function index()
    {
        // For now, assuming User is linked to Personil via email or some other mechanism.
        // If not, we might need to find the personil_id based on Auth::user()->email

        $user     = Auth::user();
        $personil = Personil::where('email', $user->email)->first();

        if (! $personil) {
            return view('pages.pemutu.mykpi.index', ['kpis' => collect([]), 'error' => 'No Personil data found for your account.']);
        }

        $kpis = IndikatorPersonil::where('personil_id', $personil->personil_id)
            ->with(['indikator'])
            ->orderBy('year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return view('pages.pemutu.mykpi.index', compact('kpis', 'personil'));
    }

    public function edit($id)
    {
        // Hashid binding should handle decoding if set up, otherwise we might need to decode manually
        // But IndikatorPersonil uses HashidBinding trait, so implicit binding might work if route key is set.
        // If simply passing ID, we can find it.

        // Let's assume ID is passed (we'll check Route logic later)
        $kpi = IndikatorPersonil::with('indikator')->findOrFail($id);

        // Security check
        $user     = Auth::user();
        $personil = Personil::where('email', $user->email)->first();
        if ($kpi->personil_id !== $personil->personil_id) {
            abort(403);
        }

        return view('pages.pemutu.mykpi.edit', compact('kpi'));
    }

    public function update(Request $request, $id)
    {
        $kpi = IndikatorPersonil::findOrFail($id);

        // Security check
        $user     = Auth::user();
        $personil = Personil::where('email', $user->email)->first();
        if ($kpi->personil_id !== $personil->personil_id) {
            abort(403);
        }

        $validated = $request->validate([
            'realization' => 'nullable|string',
            'score'       => 'nullable|numeric|min:0|max:100',
            'attachment'  => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $path                    = $request->file('attachment')->store('pemutu/kpi_evidence', 'public');
            $validated['attachment'] = $path;
        }

        $validated['status'] = 'submitted'; // Auto-submit on save for now

        $kpi->update($validated);

        return redirect()->route('pemutu.mykpi.index')->with('success', 'KPI Updated Successfully');
    }
}
