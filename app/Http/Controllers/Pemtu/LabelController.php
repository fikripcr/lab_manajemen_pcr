<?php
namespace App\Http\Controllers\Pemtu;

use App\Http\Controllers\Controller;
use App\Models\Pemtu\Label;
use App\Models\Pemtu\LabelType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class LabelController extends Controller
{
    public function index()
    {
        $types = LabelType::orderBy('name')->get();
        return view('pages.pemtu.labels.index', compact('types'));
    }

    public function paginate(Request $request)
    {
        $data = Label::with('type')->select('label.*');

        if ($request->has('type_id') && $request->type_id != '') {
            $data->where('type_id', $request->type_id);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                $color = $row->type->color ?? 'blue';
                return '<span class="badge bg-' . $color . '-lt text-' . $color . '">' . e($row->name) . '</span>';
            })
            ->editColumn('description', function ($row) {
                return $row->description ?: '-';
            })
            ->editColumn('type_id', function ($row) {
                return $row->type ? $row->type->name : '-';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pemtu.labels.edit', $row->label_id),
                    'editModal' => true,
                    'deleteUrl' => route('pemtu.labels.destroy', $row->label_id),
                ])->render();
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    public function create()
    {
        $types = LabelType::all();
        return view('pages.pemtu.labels.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_id'     => 'required|exists:label_types,labeltype_id',
            'name'        => 'required|string|max:100',
            'slug'        => 'nullable|string|max:100', // Auto-generated if empty
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Label::create($data);

        return response()->json([
            'message' => 'Label created successfully.',
        ]);
    }

    public function edit($id)
    {
        $label = Label::findOrFail($id);
        $types = LabelType::all();
        return view('pages.pemtu.labels.edit', compact('label', 'types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type_id'     => 'required|exists:label_types,labeltype_id',
            'name'        => 'required|string|max:100',
            'slug'        => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $label = Label::findOrFail($id);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $label->update($data);

        return response()->json([
            'message' => 'Label updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $label = Label::findOrFail($id);
        $label->delete();

        return response()->json([
            'success' => true,
            'message' => 'Label deleted successfully.',
        ]);
    }
}
