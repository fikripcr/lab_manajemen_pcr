<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\RenopRequest;
use App\Models\Pemutu\Indikator;
use App\Services\Pemutu\IndikatorService;

class RenopController extends Controller
{
    public function __construct(protected IndikatorService $indikatorService)
    {}
    public function index()
    {
        $renops = Indikator::where('type', 'renop')
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->where('type', 'renop')->with(['children']);
            }])
            ->get();

        return view('pages.pemutu.renop.index', compact('renops'));
    }

    public function create()
    {
        $parents = Indikator::where('type', 'renop')->pluck('indikator', 'indikator_id');
        $model   = new Indikator();
        return view('pages.pemutu.renop.create-edit-ajax', compact('parents', 'model'));
    }

    public function store(RenopRequest $request)
    {
        $this->indikatorService->createIndikator($request->validated());
        return jsonSuccess('Renop created successfully', route('pemutu.renop.index'));
    }

    public function edit(Indikator $renop)
    {
        $parents = Indikator::where('type', 'renop')
            ->where('indikator_id', '!=', $renop->indikator_id)
            ->pluck('indikator', 'indikator_id');
        $model = $renop;
        return view('pages.pemutu.renop.create-edit-ajax', compact('parents', 'model'));
    }

    public function update(RenopRequest $request, Indikator $renop)
    {
        $this->indikatorService->updateIndikator($renop->indikator_id, $request->validated());
        return jsonSuccess('Renop updated successfully', route('pemutu.renop.index'));
    }

    public function destroy(Indikator $renop)
    {
        $this->indikatorService->deleteIndikator($renop->indikator_id);
        return jsonSuccess('Renop deleted successfully.');
    }
}
