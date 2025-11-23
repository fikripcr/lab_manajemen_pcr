<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Models\Sys\ErrorLog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ErrorLogController extends Controller
{
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
        $errorLogs = ErrorLog::with('user')->orderBy('created_at', 'desc');

        // Apply filters if provided
        if ($request->filled('level')) {
            $errorLogs->where('level', $request->level);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $errorLogs->whereBetween('created_at', [
                $request->start_date,
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('start_date')) {
            $errorLogs->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $errorLogs->whereDate('created_at', '<=', $request->end_date);
        }

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
            ->addColumn('actions', function ($log) {
                return '
                    <div class="d-flex">
                        <a href="' . route('sys.error-log.show', encryptId($log->id)) . '"
                           class="text-success"
                           title="View Details">
                            <i class="bx bx-show"></i>
                        </a>
                    </div>';
            })
            ->rawColumns(['message', 'error_type', 'user_info', 'actions'])
            ->make(true);
    }

    /**
     * Display the specified error log.
     */
    public function show($id)
    {
        $realId = decryptId($id);
        $errorLog = ErrorLog::findOrFail($realId);

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
        ErrorLog::truncate();

        return response()->json([
            'success' => true,
            'message' => 'All error logs cleared successfully'
        ]);
    }
}
