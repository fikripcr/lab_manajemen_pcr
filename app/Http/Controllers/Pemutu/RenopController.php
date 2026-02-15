<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\Indikator;
use Illuminate\Http\Request;

class RenopController extends Controller
{
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
        return view('pages.pemutu.renop.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'indikator' => 'required|string',
            'target'    => 'required|string',
            'parent_id' => 'nullable|exists:pemutu_indikator,indikator_id',
            'seq'       => 'nullable|integer',
        ]);

        $validated['type'] = 'renop';

        return redirect()->route('pemutu.renop.index')->with('success', 'Renop created successfully');
    }
}
