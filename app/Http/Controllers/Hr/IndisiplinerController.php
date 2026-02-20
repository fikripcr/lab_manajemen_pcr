<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\IndisiplinerRequest;
use App\Models\Hr\Indisipliner;
use App\Models\Hr\IndisiplinerPegawai;
use App\Models\Hr\JenisIndisipliner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class IndisiplinerController extends Controller
{
    public function index()
    {
        $jenisIndisipliner = JenisIndisipliner::orderBy('jenis_indisipliner')->get();

        return view('pages.hr.indisipliner.index', compact('jenisIndisipliner'));
    }

    public function data(Request $request)
    {
        $query = Indisipliner::with(['jenisIndisipliner', 'indisiplinerPegawai.pegawai.latestDataDiri'])
            ->select('hr_indisipliner.*')
            ->filterByYear($request->input('f_tahun'))
            ->latest('tgl_indisipliner');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('tgl_indisipliner', function ($row) {
                return $row->tgl_indisipliner ? $row->tgl_indisipliner->format('d-m-Y') : '-';
            })
            ->addColumn('jenis', function ($row) {
                return $row->jenisIndisipliner->jenis_indisipliner ?? '-';
            })
            ->addColumn('pegawai', function ($row) {
                $badges = [];
                foreach ($row->indisiplinerPegawai as $ip) {
                    $inisial  = $ip->pegawai->latestDataDiri->inisial ?? 'N/A';
                    $badges[] = '<span class="badge bg-red-lt me-1">' . e($inisial) . '</span>';
                }
                return implode('', $badges);
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.indisipliner.edit', ['indisipliner' => $row->hashid]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.indisipliner.destroy', ['indisipliner' => $row->hashid]),
                ])->render();
            })
            ->rawColumns(['pegawai', 'action'])
            ->make(true);
    }

    public function create()
    {
        $jenisIndisipliner = JenisIndisipliner::orderBy('jenis_indisipliner')->get();

        return view('pages.hr.indisipliner.create', compact('jenisIndisipliner'));
    }

    public function store(IndisiplinerRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Create indisipliner record
            $indisipliner = Indisipliner::create([
                'jenisindisipliner_id' => $validated['jenisindisipliner_id'],
                'tgl_indisipliner'     => $validated['tgl_indisipliner'],
                'keterangan'           => $validated['keterangan'] ?? null,
            ]);

            // Handle file upload
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $path = $file->store('hr/indisipliner', 'public');
                $indisipliner->update(['bukti' => $path]);
            }

            // Attach pegawai
            foreach ($validated['pegawai_id'] as $pegawaiId) {
                IndisiplinerPegawai::create([
                    'indisipliner_id' => $indisipliner->indisipliner_id,
                    'pegawai_id'      => $pegawaiId,
                ]);
            }

            DB::commit();
            return jsonSuccess('Indisipliner berhasil dibuat.');
        } catch (Exception $e) {
            DB::rollBack();
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(Indisipliner $indisipliner)
    {
        $jenisIndisipliner = JenisIndisipliner::orderBy('jenis_indisipliner')->get();

        // Load pegawai with their data diri for the select2
        $indisipliner->load('indisiplinerPegawai.pegawai.latestDataDiri');

        return view('pages.hr.indisipliner.edit', compact('indisipliner', 'jenisIndisipliner'));
    }

    public function update(IndisiplinerRequest $request, Indisipliner $indisipliner)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Update indisipliner record
            $indisipliner->update([
                'jenisindisipliner_id' => $validated['jenisindisipliner_id'],
                'tgl_indisipliner'     => $validated['tgl_indisipliner'],
                'keterangan'           => $validated['keterangan'] ?? null,
            ]);

            // Handle file upload
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $path = $file->store('hr/indisipliner', 'public');
                $indisipliner->update(['bukti' => $path]);
            }

            // Sync pegawai
            $indisipliner->indisiplinerPegawai()->delete();
            foreach ($validated['pegawai_id'] as $pegawaiId) {
                IndisiplinerPegawai::create([
                    'indisipliner_id' => $indisipliner->indisipliner_id,
                    'pegawai_id'      => $pegawaiId,
                ]);
            }

            DB::commit();
            return jsonSuccess('Indisipliner berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(Indisipliner $indisipliner)
    {
        try {
            DB::beginTransaction();

            // Soft delete associated pegawai records
            IndisiplinerPegawai::where('indisipliner_id', $indisipliner->indisipliner_id)->delete();

            $indisipliner->delete();

            DB::commit();

            return jsonSuccess('Data indisipliner berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            return jsonError('Gagal menghapus data: ' . $e->getMessage(), 500);
        }
    }
}
