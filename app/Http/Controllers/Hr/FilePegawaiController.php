<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\FilePegawaiStoreRequest;
use App\Services\Hr\FilePegawaiService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FilePegawaiController extends Controller
{
    protected $FilePegawaiService;

    public function __construct(FilePegawaiService $FilePegawaiService)
    {
        $this->FilePegawaiService = $FilePegawaiService;
    }

    /**
     * Display a listing of files for a specific employee via AJAX.
     */
    public function index(Request $request, $pegawai_id)
    {
        $pegawaiId = decryptId($pegawai_id);
        $query     = $this->FilePegawaiService->getQuery($pegawaiId);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category', function ($row) {
                return $row->jenisfile->jenisfile ?? '-';
            })
            ->addColumn('filename', function ($row) {
                $media = $row->getFirstMedia('file_pegawai');
                return $media ? $media->file_name : '-';
            })
            ->addColumn('size', function ($row) {
                $media = $row->getFirstMedia('file_pegawai');
                return $media ? formatBytes($media->size) : '-';
            })
            ->addColumn('action', function ($row) {
                $media = $row->getFirstMedia('file_pegawai');
                $html  = '';

                if ($media) {
                    $html .= '<a href="' . $media->getUrl() . '" target="_blank" class="btn btn-sm btn-icon btn-ghost-primary me-1" title="Download">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                    </a>';
                }

                $html .= '<button type="button" onclick="deleteFile(\'' . $row->hashid . '\')" class="btn btn-sm btn-icon btn-ghost-danger" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                </button>';

                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly uploaded file.
     */
    public function store(FilePegawaiStoreRequest $request)
    {

        try {
            $pegawaiId = decryptId($request->pegawai_id);
            $this->FilePegawaiService->storeFile($pegawaiId, $request->only(['jenisfile_id', 'keterangan']), $request->file('file'));

            return jsonSuccess('File berhasil diunggah');
        } catch (Exception $e) {
            return jsonError('Gagal mengunggah file: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified file (Soft Delete).
     */
    public function destroy($id)
    {
        try {
            $decryptedId = decryptId($id);
            $this->FilePegawaiService->deleteFile($decryptedId);

            return jsonSuccess('File berhasil dihapus');
        } catch (Exception $e) {
            return jsonError('Gagal menghapus file: ' . $e->getMessage());
        }
    }
}
