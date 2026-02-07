<?php
namespace App\Http\Controllers\Pemtu;

use App\Http\Controllers\Controller;
use App\Models\Pemtu\LabelType;
use Illuminate\Http\Request;

class LabelTypeController extends Controller
{

    public function create()
    {
        return view('pages.pemtu.label-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        LabelType::create($request->all());

        return response()->json([
            'message'  => 'Label Type created successfully.',
            'redirect' => route('pemtu.labels.index'),
        ]);
    }

    public function edit($id)
    {
        $labelType = LabelType::findOrFail($id);
        return view('pages.pemtu.label-types.edit', compact('labelType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $labelType = LabelType::findOrFail($id);
        $labelType->update($request->all());

        return response()->json([
            'message'  => 'Label Type updated successfully.',
            'redirect' => route('pemtu.labels.index'),
        ]);
    }

    public function destroy($id)
    {
        $labelType = LabelType::findOrFail($id);
        $labelType->delete();

        return response()->json([
            'success'  => true,
            'message'  => 'Label Type deleted successfully.',
            'redirect' => route('pemtu.labels.index'),
        ]);
    }
}
