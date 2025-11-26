<?php
namespace App\Http\Controllers\Sys;

use App\Exports\Sys\ActivityLogExport;
use App\Http\Controllers\Controller;
use App\Models\Sys\Activity;
use App\Models\Sys\ErrorLog;
use App\Models\Sys\ServerMonitorCheck;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
                'message' => 'Test email sent successfully to ' . $user->email,
            ]);
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error sending test email: ' . $e->getMessage(), $user);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function testNotification(Request $request)
    {
        $user = auth()->user();

        try {
            // Send the test notification to the user using the existing TestNotification class
            $user->notify(new \App\Notifications\SysTestNotification());

            logActivity('test_dashboard', 'Test notification sent successfully to ' . $user->name, $user);

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully to ' . $user->name,
            ]);
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error sending test notification: ' . $e->getMessage(), $user);

            return response()->json([
                'success' => false,
                'message' => 'Error sending test notification: ' . $e->getMessage(),
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
                'user'           => $user,
                'errorLogs'      => $errorLogs,
                'activityLogs'   => $activityLogs,
                'monitoringLogs' => $monitoringLogs,
                'reportDate'     => now()->format('d M Y H:i'),
            ];

            $pdf = Pdf::loadView('pages.sys.test.test-pdf-with-qrcode', $data);

            logActivity('test_dashboard', 'Test PDF export generated successfully by ' . $user->name, $user);

            return $pdf->download('test-report-' . now()->format('Y-m-d-H-i') . '.pdf');
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error generating test PDF export: ' . $e->getMessage(), $user);

            return response()->json([
                'success' => false,
                'message' => 'Error generating test PDF export: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function testExcelExport(Request $request)
    {
        $user = auth()->user();

        try {
            // Just export the Activity Logs as a simple Excel file
            $fileName = 'activity-logs-' . now()->format('Y-m-d-H-i') . '.xlsx';

            return Excel::download(
                new ActivityLogExport(),
                $fileName
            );
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error generating test Excel export: ' . $e->getMessage(), $user);

            return response()->json([
                'success' => false,
                'message' => 'Error generating test Excel export: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function testWordExport(Request $request)
    {
        $user = auth()->user();

        try {
            // Get the 10 most recent error logs to include in the Word document
            $errorLogs = ErrorLog::orderBy('created_at', 'desc')->limit(10)->get();

            // Get the 10 most recent activity logs to include in the Word document
            $activityLogs = Activity::with(['causer:id,name', 'subject'])
                ->latest()
                ->limit(10)
                ->get();

            // Get the 10 most recent server monitoring records
            $monitoringLogs = ServerMonitorCheck::orderBy('created_at', 'desc')->limit(10)->get();

            $data = [
                'user'           => $user,
                'errorLogs'      => $errorLogs,
                'activityLogs'   => $activityLogs,
                'monitoringLogs' => $monitoringLogs,
                'reportDate'     => now()->format('d M Y H:i'),
            ];

            // Create a new Word document
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();

            // Add header
            $section->addText('Laporan Test Word dengan QR Code', [
                'bold' => true,
                'size' => 16,
            ]);
            $section->addTextBreak(1);
            $section->addText('Tanggal: ' . $data['reportDate']);

            // Add error logs
            $section->addTextBreak(1);
            $section->addText('Data Error Logs Terbaru', ['bold' => true, 'size' => 14]);
            $section->addTextBreak(1);

            // Create table for error logs
            $errorTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 50]);
            $errorTable->addRow();
            $errorTable->addCell(1000)->addText('ID');
            $errorTable->addCell(1000)->addText('Level');
            $errorTable->addCell(3000)->addText('Message');
            $errorTable->addCell(1500)->addText('File');
            $errorTable->addCell(1000)->addText('Line Number');
            $errorTable->addCell(1000)->addText('User ID');
            $errorTable->addCell(1500)->addText('Created At');

            foreach ($data['errorLogs'] as $log) {
                $errorTable->addRow();
                $errorTable->addCell(1000)->addText($log->id);
                $errorTable->addCell(1000)->addText($log->level);
                $errorTable->addCell(3000)->addText($log->message);
                $errorTable->addCell(1500)->addText($log->file);
                $errorTable->addCell(1000)->addText($log->line_number);
                $errorTable->addCell(1000)->addText($log->user_id);
                $errorTable->addCell(1500)->addText($log->created_at);
            }

            // Add activity logs
            $section->addTextBreak(1);
            $section->addText('Data Activity Logs Terbaru', ['bold' => true, 'size' => 14]);
            $section->addTextBreak(1);

            // Create table for activity logs
            $activityTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 50]);
            $activityTable->addRow();
            $activityTable->addCell(1500)->addText('Log Name');
            $activityTable->addCell(3000)->addText('Description');
            $activityTable->addCell(2000)->addText('Subject Type');
            $activityTable->addCell(1500)->addText('Causer');
            $activityTable->addCell(1500)->addText('Created At');

            foreach ($activityLogs as $log) {
                $activityTable->addRow();
                $activityTable->addCell(1500)->addText($log->log_name);
                $activityTable->addCell(3000)->addText($log->description);
                $activityTable->addCell(2000)->addText($log->subject_type);
                $activityTable->addCell(1500)->addText($log->causer->name ?? 'N/A');
                $activityTable->addCell(1500)->addText($log->created_at);
            }

            // Add monitoring logs
            $section->addTextBreak(1);
            $section->addText('Data Server Monitoring Terbaru', ['bold' => true, 'size' => 14]);
            $section->addTextBreak(1);

            // Create table for monitoring logs
            $monitoringTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 50]);
            $monitoringTable->addRow();
            $monitoringTable->addCell(2000)->addText('Check Name');
            $monitoringTable->addCell(1000)->addText('Status');
            $monitoringTable->addCell(3500)->addText('Output');
            $monitoringTable->addCell(1500)->addText('Host');
            $monitoringTable->addCell(1500)->addText('Created At');

            foreach ($monitoringLogs as $log) {
                $monitoringTable->addRow();
                $monitoringTable->addCell(2000)->addText($log->check_name);
                $monitoringTable->addCell(1000)->addText($log->status);
                $monitoringTable->addCell(3500)->addText($log->output);
                $monitoringTable->addCell(1500)->addText($log->host);
                $monitoringTable->addCell(1500)->addText($log->created_at);
            }

            // Add QR code information
            $section->addTextBreak(2);
            $section->addText('QR Code Informasi Dokumen', ['bold' => true, 'size' => 14]);

            // Generate QR code content
            $qrCodeText = route('sys.test.index');

            // Create temp folder for QR code image
            $tempDir = storage_path('app/temp');
            if (! file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $qrCodePath = $tempDir . '/qrcode_' . uniqid() . '.png';

            // Generate QR code image
            QrCode::format('png')->size(200)->generate($qrCodeText, $qrCodePath);

            // Verify if QR code file was generated before adding to document
            if (file_exists($qrCodePath)) {
                // Add QR code image to document
                $section->addImage($qrCodePath, [
                    'width'     => 150,
                    'height'    => 150,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                ]);
            } else {
                // Fallback if QR code wasn't generated properly
                $section->addText('QR Code image could not be generated.', ['italic' => true]);
            }

            $section->addText('Kode QR ini berisi URL ke halaman Test Features: ' . $qrCodeText);

            // Add footer
            $section->addTextBreak(2);
            $section->addText('Dokumen ini dibuat secara otomatis oleh sistem pada ' . now()->format('d M Y H:i:s'));
            $section->addText('Generated by ' . $user->name . ' (' . $user->email . ')');

            // Save the document temporarily
            $fileName = 'test-report-' . now()->format('Y-m-d-H-i') . '.docx';
            $filePath = storage_path('app/' . $fileName);

            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($filePath);

            // Clean up temp QR code file
            if (file_exists($qrCodePath)) {
                unlink($qrCodePath);
            }

            logActivity('test_dashboard', 'Test Word export generated successfully by ' . $user->name, $user);

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error generating test Word export: ' . $e->getMessage(), $user);

            return response()->json([
                'success' => false,
                'message' => 'Error generating test Word export: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function generateQrCode(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'size' => 'required|integer|min:100|max:500',
        ]);

        $text = $request->input('text');
        $size = $request->input('size', 200);

        // Generate QR code as SVG using BaconQrCode
        $qrCodeSvg = QrCode::size($size)->generate($text);

        return response()->json([
            'success' => true,
            'svg'     => base64_encode($qrCodeSvg),
            'message' => 'QR code generated successfully',
        ]);
    }

    public function qrCode()
    {
        return view('pages.sys.test.qrcode');
    }

    public function showQrCode(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'size' => 'nullable|integer|min:100|max:500',
        ]);

        $text = $request->input('text');
        $size = $request->input('size', 200);

        // Generate QR code as SVG using BaconQrCode
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle($size),
            new \BaconQrCode\Renderer\Color\ForegroundColor(0, 0, 0),
            new \BaconQrCode\Renderer\Color\BackgroundColor(255, 255, 255)
        );
        $writer    = new \BaconQrCode\Writer($renderer);
        $qrCodeSvg = $writer->writeString($text);

        return view('pages.sys.test.qrcode-display', compact('qrCodeSvg', 'text', 'size'));
    }

    public function downloadQrCode($filename)
    {
        // Since we're no longer saving files, this function becomes unnecessary
        return response()->json([
            'success' => false,
            'message' => 'QR code file download is deprecated in favor of SVG output',
        ], 404);
    }

    public function tinymce()
    {
        return view('pages.sys.test.tinymce');
    }

    public function features()
    {
        return view('pages.sys.test.features');
    }
}
