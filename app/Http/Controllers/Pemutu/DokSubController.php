<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DokSubRequest;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Services\Pemutu\DokSubService;
use App\Services\Pemutu\DokumenService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DokSubController extends Controller
{
    public function __construct(
        protected DokSubService $dokSubService,
        protected DokumenService $dokumenService,
    ) {}

    public function show(DokSub $dokSub)
    {
        $parent      = $dokSub->dokumen;
        $parentJenis = strtolower(trim($parent->jenis));

        // Determine Child Type based on Parent Dokumen Type
        $childLabel = match ($parentJenis) {
            'visi'    => 'Poin',
            'misi'    => 'Poin',
            'rjp'     => 'Poin',
            'renstra' => 'Poin',
            'renop'   => 'Butir Standar',
            'standar' => 'Butir Standar',
            default   => 'Turunan'
        };

        // DokSub usually has markers/indicators if specified
        $showIndikators = $dokSub->is_hasilkan_indikator;

        $activeSubTab = \request()->get('subtab', 'overview');

        // Prepare normalized data for unified component
        $data = [
            'item'           => $dokSub,
            'isDokumen'      => false, // This is a DokSub
            'parent'         => $parent,
            'childLabel'     => $childLabel,
            'showIndikators' => $showIndikators,
            'activeSubTab'   => $activeSubTab,
        ];

        // Handle AJAX response
        if (\request()->ajax() || \request()->has('ajax')) {
            return \view('pages.pemutu.shared._detail_panel', $data);
        }

        // Handle Full Page Load
        return \view('pages.pemutu.dok-subs.detail', $data);
    }

    public function create(Request $request)
    {
        try {
            $dokId   = (int) decryptIdIfEncrypted($request->query('dok_id'));
            $dokumen = $this->dokumenService->getDokumenById($dokId);
            if (! $dokumen) {
                \abort(404);
            }

            $dokSub = new DokSub(); // Empty model for create case
            return \view('pages.pemutu.dok-subs.create-edit-ajax', compact('dokumen', 'dokSub'));
        } catch (Exception $e) {
            \abort(404);
        }
    }

    public function store(DokSubRequest $request)
    {
        try {
            $data                          = $request->validated();
            $data['is_hasilkan_indikator'] = $request->boolean('is_hasilkan_indikator');
            $dokSub                        = $this->dokSubService->createDokSub($data);

            logActivity('pemutu', "Menambah sub-dokumen baru: {$dokSub->judul}");

            return jsonSuccess('Sub-Document created successfully.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan sub-dokumen: ' . $e->getMessage());
        }
    }

    public function edit(DokSub $dokSub)
    {
        return view('pages.pemutu.dok-subs.create-edit-ajax', compact('dokSub'));
    }

    public function update(DokSubRequest $request, DokSub $dokSub)
    {
        try {
            $data                          = $request->validated();
            $data['is_hasilkan_indikator'] = $request->boolean('is_hasilkan_indikator');
            $this->dokSubService->updateDokSub($dokSub->doksub_id, $data);

            logActivity('pemutu', "Memperbarui sub-dokumen: {$dokSub->judul}");

            return jsonSuccess('Sub-Document updated successfully.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui sub-dokumen: ' . $e->getMessage());
        }
    }

    public function destroy(DokSub $dokSub)
    {
        try {
            $judul = $dokSub->judul;
            $this->dokSubService->deleteDokSub($dokSub->doksub_id);

            logActivity('pemutu', "Menghapus sub-dokumen: {$judul}");

            return jsonSuccess('Sub-Document deleted successfully.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus sub-dokumen: ' . $e->getMessage());
        }
    }

    public function data(Request $request, Dokumen $dokumen)
    {
        if ($request->ajax()) {
            try {
                $query = $this->dokSubService->getFilteredQuery(['dok_id' => $dokumen->dok_id]);

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('seq', function ($row) {
                        return '<span class="badge badge-outline text-muted">' . $row->seq . '</span>';
                    })
                    ->addColumn('judul', function ($row) {
                        $detailUrl = route('pemutu.dok-subs.show', $row);
                        return '
                            <a href="' . $detailUrl . '" class="fw-medium text-reset" title="Lihat Detail & Turunan">
                                ' . $row->judul . '
                            </a>
                            <div class="text-muted small text-truncate" style="max-width: 300px;">' . strip_tags($row->isi) . '</div>
                        ';
                    })
                    ->addColumn('action', function ($row) {
                        return view('components.tabler.datatables-actions', [
                            'viewUrl'   => route('pemutu.dok-subs.show', $row->encrypted_doksub_id),
                            'editUrl'   => route('pemutu.dok-subs.edit', $row->encrypted_doksub_id),
                            'editModal' => false,
                            'deleteUrl' => route('pemutu.dok-subs.destroy', $row->encrypted_doksub_id),
                        ])->render();
                    })
                    ->rawColumns(['seq', 'judul', 'action'])
                    ->make(true);
            } catch (Exception $e) {
                logError($e);
                return jsonError('Gagal memuat data: ' . $e->getMessage());
            }
        }
    }
}
