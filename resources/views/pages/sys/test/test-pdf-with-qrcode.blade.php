<!DOCTYPE html>
<html>
<head>
    <title>Test PDF Export with QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Test PDF dengan QR Code</h1>
        <p>Tanggal: {{ $reportDate }}</p>
    </div>

    <div class="content">
        <h2>Data Error Logs Terbaru</h2>
        @if(count($errorLogs) > 0)
            <table border="1" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="padding: 8px;">ID</th>
                        <th style="padding: 8px;">Level</th>
                        <th style="padding: 8px;">Message</th>
                        <th style="padding: 8px;">File</th>
                        <th style="padding: 8px;">Line Number</th>
                        <th style="padding: 8px;">User ID</th>
                        <th style="padding: 8px;">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($errorLogs as $log)
                        <tr>
                            <td style="padding: 5px;">{{ $log->id }}</td>
                            <td style="padding: 5px;">{{ $log->level }}</td>
                            <td style="padding: 5px;">{{ $log->message }}</td>
                            <td style="padding: 5px;">{{ $log->file }}</td>
                            <td style="padding: 5px;">{{ $log->line_number }}</td>
                            <td style="padding: 5px;">{{ $log->user_id }}</td>
                            <td style="padding: 5px;">{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada error logs.</p>
        @endif

        <h2>Data Activity Logs Terbaru</h2>
        @if(count($activityLogs) > 0)
            <table border="1" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="padding: 8px;">ID</th>
                        <th style="padding: 8px;">Log Name</th>
                        <th style="padding: 8px;">Description</th>
                        <th style="padding: 8px;">Subject Type</th>
                        <th style="padding: 8px;">Causer</th>
                        <th style="padding: 8px;">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activityLogs as $log)
                        <tr>
                            <td style="padding: 5px;">{{ $log->id }}</td>
                            <td style="padding: 5px;">{{ $log->log_name }}</td>
                            <td style="padding: 5px;">{{ $log->description }}</td>
                            <td style="padding: 5px;">{{ $log->subject_type }}</td>
                            <td style="padding: 5px;">{{ $log->causer->name ?? 'N/A' }}</td>
                            <td style="padding: 5px;">{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada activity logs.</p>
        @endif

        <h2>Data Server Monitoring Terbaru</h2>
        @if(count($monitoringLogs) > 0)
            <table border="1" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="padding: 8px;">ID</th>
                        <th style="padding: 8px;">Check Name</th>
                        <th style="padding: 8px;">Status</th>
                        <th style="padding: 8px;">Output</th>
                        <th style="padding: 8px;">Host</th>
                        <th style="padding: 8px;">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monitoringLogs as $log)
                        <tr>
                            <td style="padding: 5px;">{{ $log->id }}</td>
                            <td style="padding: 5px;">{{ $log->check_name }}</td>
                            <td style="padding: 5px;">{{ $log->status }}</td>
                            <td style="padding: 5px;">{{ $log->output }}</td>
                            <td style="padding: 5px;">{{ $log->host }}</td>
                            <td style="padding: 5px;">{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada monitoring logs.</p>
        @endif
    </div>

    <div class="qr-code-section" style="text-align: center; margin-top: 30px; padding: 20px; border: 1px solid #ddd; background-color: #f9f9f9;">
        <h3>QR Code Informasi Dokumen</h3>
        <div style="margin: 0 auto; width: 200px; display: flex; justify-content: center; align-items: center;">
            <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code" style=" height: auto;" />
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem pada {{ now()->format('d M Y H:i:s') }}</p>
        <p>Generated by {{ $user->name }} ({{ $user->email }})</p>
    </div>
</body>
</html>
