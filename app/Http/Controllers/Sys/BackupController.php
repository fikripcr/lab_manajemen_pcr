<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\BackupStoreRequest;
use App\Services\Sys\BackupService;

class BackupController extends Controller
{
    public function __construct(protected BackupService $backupService)
    {}

    /**
     * Display a listing of backups
     */
    public function index()
    {
        $backups = $this->backupService->getBackupList();

        return view('pages.sys.backup.index', compact('backups'));
    }

    /**
     * Store a newly created backup
     */
    public function store(BackupStoreRequest $request)
    {
        $type = $request->input('type', 'files');
        $this->backupService->createBackup($type);

        return jsonSuccess('Backup created successfully');
    }

    /**
     * Download a backup file
     */
    public function download($filename)
    {
        $filePath = $this->backupService->getBackupFilePath($filename);

        return response()->download($filePath);
    }

    /**
     * Remove a backup file
     */
    public function destroy($filename)
    {
        $this->backupService->deleteBackup($filename);

        return jsonSuccess('Backup deleted successfully');
    }
}
