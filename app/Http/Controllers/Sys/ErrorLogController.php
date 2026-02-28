<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Models\Sys\ErrorLog;
use App\Services\Sys\ErrorLogService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ErrorLogController extends Controller
{
    public function __construct(
        protected ErrorLogService $errorLogService
    ) {
    }

    /**
     * Display a listing of error logs.
     */
    public function index()
    {
        return view('pages.sys.error-log.index');
    }

    /**
     * Process datatables ajax request for error logs.
     */
    public function paginate(Request $request)
    {
        $filters = [
            'level'      => $request->get('level'),
            'start_date' => $request->get('start_date'),
            'end_date'   => $request->get('end_date'),
        ];

        $errorLogs = $this->errorLogService->getFilteredQuery($filters);

        return DataTables::of($errorLogs)
            ->addIndexColumn()
            ->editColumn('message', function ($log) {
                // Limit message length and add ellipsis if needed
                $message = strlen($log->message) > 300
                    ? substr($log->message, 0, 300) . '...'
                    : $log->message;
                return '<span title="' . e($log->message) . '">' . e($message) . '</span>';
            })
            ->addColumn('error_type', function ($log) {
                $errorClass = class_basename($log->exception_class);

                // Extract SQLSTATE code from context if available
                $sqlState = null;
                if (isset($log->context['sql_state'])) {
                    $sqlState = $log->context['sql_state'];
                } elseif (isset($log->context['error_info']['SQLSTATE'])) {
                    $sqlState = $log->context['error_info']['SQLSTATE'];
                } elseif (preg_match('/SQLSTATE\[(\w+)\]/', $log->message, $matches)) {
                    $sqlState = $matches[1];
                }

                $display = '<span class="text-danger" title="' . e($log->exception_class) . '">' . e($errorClass) . '</span>';

                if ($sqlState) {
                    $display .= '<br><small class="text-muted">[' . e($sqlState) . ']</small>';
                }

                return $display;
            })
            ->addColumn('user_info', function ($log) {
                if ($log->user) {
                    // If user is authenticated, show user's name
                    return '<span class="text-primary" title="User: ' . e($log->user->name) . '">' . e($log->user->name) . '</span>';
                } else {
                    // If no user (system error), show "System"
                    return '<span class="text-muted" title="System error">System</span>';
                }
            })
            ->editColumn('created_at', function ($log) {
                return formatTanggalIndo($log->created_at);
            })
            ->addColumn('code', function ($log) {
                return $log->context['code'] ?? '-';
            })
            ->addColumn('file_short', function ($log) {
                // Return short file path with line number
                $shortFile = basename($log->file ?? '');
                return '<span title="' . e($log->file) . '">' . e($shortFile) . ':' . e($log->line) . '</span>';
            })
            ->addColumn('action', function ($log) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl' => route('sys.error-log.show', encryptId($log->id)),
                ])->render();
            })
            ->rawColumns(['message', 'error_type', 'user_info', 'file_short', 'action'])
            ->make(true);
    }

    /**
     * Display the specified error log.
     */
    public function show(ErrorLog $errorLog)
    {
        $errorLog = $this->errorLogService->findOrFail($errorLog->id);

        return view('pages.sys.error-log.show', compact('errorLog'));
    }

    /**
     * Remove the specified error log from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Clear all error logs.
     */
    public function clearAll(Request $request)
    {
        $this->errorLogService->clearAllErrorLogs();

        return jsonSuccess('All error logs cleared successfully');
    }
}
