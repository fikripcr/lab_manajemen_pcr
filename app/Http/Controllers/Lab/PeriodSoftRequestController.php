<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\PeriodSoftRequest;
use App\Models\Lab\Semester;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PeriodSoftRequestController extends Controller
{
    public function index()
    {
        return view('pages.lab.periode-request.index');
    }

    public function paginate(Request $request)
    {
        $query = PeriodSoftRequest::with('semester');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('semester', function ($row) {
                return $row->semester ? $row->semester->tahun_ajaran . ' - ' . $row->semester->semester : '-';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->editColumn('date_range', function ($row) {
                return formatTanggalIndo($row->start_date) . ' - ' . formatTanggalIndo($row->end_date);
            })
            ->addColumn('action', function ($row) {
                $encryptedId = encryptId($row->periodsoftreq_id);
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.periode-request.edit', $encryptedId),
                    'deleteUrl' => route('lab.periode-request.destroy', $encryptedId),
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        $semesters = Semester::all();
        return view('pages.lab.periode-request.create', compact('semesters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester_id'  => 'required|exists:lab_semesters,semester_id',
            'nama_periode' => 'required|string|max:191',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'is_active'    => 'boolean',
        ]);

        try {
            if ($request->has('is_active') && $request->is_active) {
                // Deactivate other periods if this one is active
                PeriodSoftRequest::where('is_active', true)->update(['is_active' => false]);
            }

            PeriodSoftRequest::create($validated);
            return jsonSuccess('Periode Request berhasil dibuat.', route('lab.periode-request.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $realId    = decryptId($id);
        $period    = PeriodSoftRequest::findOrFail($realId);
        $semesters = Semester::all();
        return view('pages.lab.periode-request.edit', compact('period', 'semesters'));
    }

    public function update(Request $request, $id)
    {
        $realId = decryptId($id);
        $period = PeriodSoftRequest::findOrFail($realId);

        $validated = $request->validate([
            'semester_id'  => 'required|exists:lab_semesters,semester_id',
            'nama_periode' => 'required|string|max:191',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'is_active'    => 'boolean',
        ]);

        try {
            if ($request->has('is_active') && $request->is_active) {
                PeriodSoftRequest::where('is_active', true)
                    ->where('periodsoftreq_id', '!=', $realId)
                    ->update(['is_active' => false]);
            }

            $period->update($validated);
            return jsonSuccess('Periode Request berhasil diperbarui.', route('lab.periode-request.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $realId = decryptId($id);
            PeriodSoftRequest::destroy($realId);
            return jsonSuccess('Periode Request berhasil dihapus.', route('lab.periode-request.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
