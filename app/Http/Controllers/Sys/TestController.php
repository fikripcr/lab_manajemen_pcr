<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Models\Sys\ErrorLog;
use App\Models\Sys\Activity;
use App\Models\Sys\ServerMonitorCheck;
use App\Notifications\CustomNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class TestController extends Controller
{
    public function index()
    {
        return view('pages.sys.test.index');
    }

    public function testEmail(Request $request)
    {
        $user = auth()->user();

        try {
            // Send a test email to the current user
            Mail::raw(
                'This is a test email sent from the Testing Dashboard.', 
                function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Test Email from Testing Dashboard');
                }
            );

            logActivity('test_dashboard', 'Test email sent successfully to ' . $user->email, $user);

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $user->email
            ]);
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error sending test email: ' . $e->getMessage(), $user);

            return response()->json([
                'success' => false,
                'message' => 'Error sending test email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testNotification(Request $request)
    {
        $user = auth()->user();

        try {
            // Send the test notification to the user using the existing TestNotification class
            $user->notify(new \App\Notifications\TestNotification());

            logActivity('test_dashboard', 'Test notification sent successfully to ' . $user->name, $user);

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully to ' . $user->name
            ]);
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error sending test notification: ' . $e->getMessage(), $user);

            return response()->json([
                'success' => false,
                'message' => 'Error sending test notification: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testPdfExport(Request $request)
    {
        $user = auth()->user();

        try {
            // Get the 10 most recent error logs to include in the PDF
            $errorLogs = ErrorLog::orderBy('created_at', 'desc')->limit(10)->get();

            // Get the 10 most recent activity logs to include in the PDF
            $activityLogs = Activity::with(['causer:id,name', 'subject'])
                ->latest()
                ->limit(10)
                ->get();

            // Get the 10 most recent server monitoring records
            $monitoringLogs = ServerMonitorCheck::orderBy('created_at', 'desc')->limit(10)->get();

            $data = [
                'user' => $user,
                'errorLogs' => $errorLogs,
                'activityLogs' => $activityLogs,
                'monitoringLogs' => $monitoringLogs,
                'reportDate' => now()->format('d M Y H:i'),
            ];

            $pdf = Pdf::loadView('pages.sys.test.test-pdf', $data);

            logActivity('test_dashboard', 'Test PDF export generated successfully by ' . $user->name, $user);

            return $pdf->download('test-report-' . now()->format('Y-m-d-H-i') . '.pdf');
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error generating test PDF export: ' . $e->getMessage(), $user);

            return response()->json([
                'success' => false,
                'message' => 'Error generating test PDF export: ' . $e->getMessage()
            ], 500);
        }
    }
}