<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\BackupStoreRequest;
use App\Services\Sys\BackupService;
use Exception;

class BackupController extends Controller
{
    protected $BackupService;

    public function __construct(BackupService $BackupService)
    {
        $this->BackupService = $BackupService;
    }

    /**
     * Display a listing of backups
     */
    public function index()
    {
        $backups = $this->BackupService->getBackupList();

        return view('pages.sys.backup.index', compact('backups'));
    }

    /**
     * Store a newly created backup
     */
    public function store(BackupStoreRequest $request)
    {
        $type = $request->input('type', 'files');

        try {
            $this->BackupService->createBackup($type);

            return jsonSuccess('Backup created successfully');
        } catch (Exception $e) {
            logError($e, 'error', [
                'backup_type' => $type,
                'description' => 'Error during backup creation',
            ]);

            return jsonError('Failed to create backup: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Download a backup file
     */
    public function download($filename)
    {
        try {
            $filePath = $this->BackupService->getBackupFilePath($filename);

            return response()->download($filePath);
        } catch (Exception $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Remove a backup file
     */
    public function destroy($filename)
    {
        try {
            $this->BackupService->deleteBackup($filename);

            return jsonSuccess('Backup deleted successfully');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 404);
        }
    }
}
