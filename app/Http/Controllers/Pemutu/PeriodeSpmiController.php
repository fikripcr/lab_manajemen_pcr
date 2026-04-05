<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PeriodeSpmiRequest;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;

class PeriodeSpmiController extends Controller
{
    public function __construct(
        protected PeriodeSpmiService $periodeSpmiService
    ) {
        $this->middleware('permission:pemutu.periode-spmi.view')->only(['index']);
        $this->middleware('permission:pemutu.periode-spmi.create')->only(['create', 'store']);
        $this->middleware('permission:pemutu.periode-spmi.update')->only(['edit', 'update']);
        $this->middleware('permission:pemutu.periode-spmi.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $pageTitle = 'Periode SPMI';

        $years = $this->periodeSpmiService->getAvailableYears();
        $defaultYear = date('Y');

        // Ensure we always have at least the default year in the list
        if ($years->isEmpty()) {
            $years->push($defaultYear);
        }

        // If current year not in available years, pick the latest one if available
        if (! $years->contains($defaultYear) && $years->isNotEmpty()) {
            $defaultYear = $years->first();
        }

        $selectedYear = $request->get('year', $defaultYear);
        $periodes = $this->periodeSpmiService->getAll($selectedYear);

        return view('pages.pemutu.periode_spmi.index', compact('pageTitle', 'periodes', 'years', 'selectedYear'));
    }

    public function create()
    {
        $periodeSpmi = new PeriodeSpmi;

        return view('pages.pemutu.periode_spmi.create-edit-ajax', compact('periodeSpmi'));
    }

    public function store(PeriodeSpmiRequest $request)
    {
        $this->periodeSpmiService->store($request->validated());

        return jsonSuccess('Periode SPMI berhasil ditambahkan', route('pemutu.periode-spmi.index'));
    }

    public function edit(PeriodeSpmi $periodeSpmi)
    {
        return view('pages.pemutu.periode_spmi.create-edit-ajax', compact('periodeSpmi'));
    }

    public function update(PeriodeSpmiRequest $request, PeriodeSpmi $periodeSpmi)
    {
        $this->periodeSpmiService->update($periodeSpmi, $request->validated());

        return jsonSuccess('Periode SPMI berhasil diperbarui', route('pemutu.periode-spmi.index'));
    }

    public function destroy(PeriodeSpmi $periodeSpmi)
    {
        $this->periodeSpmiService->delete($periodeSpmi);

        return jsonSuccess('Periode SPMI berhasil dihapus.', route('pemutu.periode-spmi.index'));
    }
}
