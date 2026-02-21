<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\FilePegawaiStoreRequest;
use App\Services\Hr\FilePegawaiService;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class FilePegawaiController extends Controller
{
    public function __construct(protected FilePegawaiService $filePegawaiService)
    {}

    /**
     * Display a listing of files for a specific employee via AJAX.
     */
    public function index($pegawai_id)
    {
        $pegawaiId = decryptIdIfEncrypted($pegawai_id);
        $query     = $this->filePegawaiService->getQuery($pegawaiId);

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
                return view('pages.hr.file_pegawai._action', compact('row'))->render();
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
            $pegawaiId = decryptIdIfEncrypted($request->pegawai_id);
            $this->filePegawaiService->storeFile($pegawaiId, $request->only(['jenisfile_id', 'keterangan']), $request->file('file'));

            return jsonSuccess('File berhasil diunggah');
        } catch (Exception $e) {
            return jsonError('Gagal mengunggah file: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified file (Soft Delete).
     */
    public function destroy(FilePegawai $file)
    {
        try {
            $this->filePegawaiService->deleteFile($file->filepegawai_id);

            return jsonSuccess('File berhasil dihapus');
        } catch (Exception $e) {
            return jsonError('Gagal menghapus file: ' . $e->getMessage());
        }
    }
}
