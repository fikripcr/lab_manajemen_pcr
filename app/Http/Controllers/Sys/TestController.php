<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sys\Activity;
use App\Models\Sys\ErrorLog;
use App\Models\Sys\ServerMonitorCheck;
use App\Exports\Sys\ActivityLogExport;
use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\TemplateProcessor;

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

            return jsonSuccess('Test email sent successfully to ' . $user->email);
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error sending test email: ' . $e->getMessage(), $user);

            return jsonError($e->getMessage(), 500);
        }
    }

    public function testNotification(Request $request)
    {
        $user = auth()->user();

        try {
            // Send the test notification to the user using the existing TestNotification class
            $user->notify(new \App\Notifications\SysTestNotification());

            logActivity('test_dashboard', 'Test notification sent successfully to ' . $user->name, $user);

            return jsonSuccess('Test notification sent successfully to ' . $user->name);
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error sending test notification: ' . $e->getMessage(), $user);

            return jsonError('Error sending test notification: ' . $e->getMessage(), 500);
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

            // Generate QR code as PNG using BaconQrCode
            $renderer = new GDLibRenderer(400);
            $writer   = new Writer($renderer);
            $pngData  = $writer->writeString('test'); // ini string biner
                                                      // Encode ke base64 untuk embed di HTML
            $base64Image = base64_encode($pngData);

            $data = [
                'user'           => $user,
                'errorLogs'      => $errorLogs,
                'activityLogs'   => $activityLogs,
                'monitoringLogs' => $monitoringLogs,
                'qrcode'         => $base64Image,
                'reportDate'     => now()->format('d M Y H:i'),
            ];

            $pdf = Pdf::loadView('pages.sys.test.test-pdf-with-qrcode', $data);

            logActivity('test_dashboard', 'Test PDF export generated successfully by ' . $user->name, $user);

            return $pdf->download('test-report-' . now()->format('Y-m-d-H-i') . '.pdf');
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error generating test PDF export: ' . $e->getMessage(), $user);

            return jsonError('Error generating test PDF export: ' . $e->getMessage(), 500);
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

            return jsonError('Error generating test Excel export: ' . $e->getMessage(), 500);
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

            // Generate QR code as PNG using BaconQrCode (consistent with other methods)
            $renderer = new GDLibRenderer(200);
            $writer   = new Writer($renderer);
            $writer->writeFile($qrCodeText, $qrCodePath);

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
                $section->addText('QR Code could not be generated.', ['italic' => true]);
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

            return jsonError('Error generating test Word export: ' . $e->getMessage(), 500);
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

        // Generate QR code as PNG using BaconQrCode
        $renderer  = new GDLibRenderer($size);
        $writer    = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($text);

        return jsonSuccess('QR code generated successfully', null, ['svg' => base64_encode($qrCodeSvg)]);
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
        $renderer  = new GDLibRenderer($size);
        $writer    = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($text);

        return view('pages.sys.test.qrcode-display', compact('qrCodeSvg', 'text', 'size'));
    }

    public function tinymce()
    {
        return view('pages.sys.test.tinymce');
    }

    public function features()
    {
        return view('pages.sys.test.features');
    }

    public function processDocxTemplate(Request $request)
    {
        $user = auth()->user();

        try {
            // Validate request - now accepts uploaded file
            $request->validate([
                'template' => 'required|file|mimes:doc,docx|max:10240', // Max 10MB
            ]);

            $file = $request->file('template');

            // Get the temporary path of the uploaded file
            $fullTemplatePath = $file->getRealPath();
            if (! $fullTemplatePath || ! file_exists($fullTemplatePath)) {
                return jsonError('Temporary file not created properly', 400);
            }

            // Define replacement variables directly in the controller
            $variables = [
                'nama'              => 'John Doe',
                'email'             => 'john@example.com',
                'tanggal_lahir'     => '1990-01-01',
                'alamat'            => 'Jl. Contoh No. 123',
                'telepon'           => '+62 123 4567',
                'pekerjaan'         => 'Software Engineer',
                'perusahaan'        => 'ABC Company',
                'tanggal_pembuatan' => now()->format('Y-m-d'),
                'waktu_pembuatan'   => now()->format('H:i:s'),
                'keterangan'        => 'Contoh keterangan',
                'judul'             => 'Contoh Judul Dokumen',
                'deskripsi'         => 'Contoh deskripsi dokumen',
            ];

            // Create a temporary file for processing
            $tempPath = storage_path('app/temp/' . uniqid() . '.docx');
            $tempDir  = dirname($tempPath);
            if (! file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Copy the original template to the temporary location
            if (! copy($fullTemplatePath, $tempPath)) {
                return jsonError('Failed to copy template file', 500);
            }

            // Use TemplateProcessor to safely replace variables
            $templateProcessor = new TemplateProcessor($tempPath);

            // Replace variables using setValue method
            foreach ($variables as $key => $value) {
                $templateProcessor->setValue($key, $value);
            }

            // Save the processed template using saveAs for more explicit file handling
            $templateProcessor->saveAs($tempPath);

            // Generate the processed DOCX file
            $fileName   = 'processed-' . time() . '.docx';
            $publicPath = storage_path('app/public/uploads/docx/' . $fileName);
            $publicDir  = dirname($publicPath);

            if (! file_exists($publicDir)) {
                mkdir($publicDir, 0755, true);
            }

            // Move the processed file from temp to public folder
            if (! copy($tempPath, $publicPath)) {
                return jsonError('Failed to move processed file to public folder', 500);
            }

            // Clean up temporary file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            // Generate the public URL for download
            logActivity('test_dashboard', 'Test DOCX template processed and saved successfully by ' . $user->name, $user);

            // Return a JSON response with the download URL
            return jsonSuccess('DOCX template processed successfully', null, [
                'download_url' => asset('templates/processed/' . basename($publicPath)),
                'filename'    => basename($publicPath),
            ]);
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error processing DOCX template: ' . $e->getMessage(), $user);

            return jsonError('Error processing DOCX template: ' . $e->getMessage(), 500);
        }
    }

    public function uploadDocxTemplate(Request $request)
    {
        $user = auth()->user();

        try {
            $request->validate([
                'template' => 'required|file|mimes:doc,docx|max:10240', // Max 10MB
            ]);

            $file     = $request->file('template');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('docx-templates', $fileName, 'local');

            logActivity('test_dashboard', 'DOCX template uploaded successfully by ' . $user->name, $user);

            return jsonSuccess('Template uploaded successfully', null, [
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]);
        } catch (\Exception $e) {
            logActivity('test_dashboard', 'Error uploading DOCX template: ' . $e->getMessage(), $user);

            return jsonError('Error uploading template: ' . $e->getMessage(), 500);
        }
    }

}
