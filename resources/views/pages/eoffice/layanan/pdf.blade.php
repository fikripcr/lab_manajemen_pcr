<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #444;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-weight: bold;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
            padding-bottom: 5px;
            color: #2c3e50;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 8px 0;
            vertical-align: top;
        }
        .label {
            width: 30%;
            font-weight: bold;
            color: #555;
        }
        .value {
            width: 70%;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
        .qr-section {
            margin-top: 40px;
            text-align: right;
        }
        .qr-box {
            display: inline-block;
            text-align: center;
            border: 1px solid #eee;
            padding: 10px;
            background: #fdfdfd;
        }
        .qr-box img {
            width: 120px;
            height: 120px;
        }
        .qr-box p {
            margin: 5px 0 0;
            font-size: 10px;
            color: #777;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BUKTI PENGOLAHAN LAYANAN</h1>
            <p>E-OFFICE - Politeknik Caltex Riau</p>
        </div>

        <div class="section">
            <div class="section-title">Informasi Layanan</div>
            <table class="info-grid">
                <tr>
                    <td class="label">No. Layanan</td>
                    <td class="value"><strong>{{ $layanan->no_layanan }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Jenis Layanan</td>
                    <td class="value">{{ $layanan->jenisLayanan->nama_layanan }}</td>
                </tr>
                <tr>
                    <td class="label">Status Akhir</td>
                    <td class="value">
                        <span class="badge">{{ $layanan->latestStatus->status_layanan ?? 'SELESAI' }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Tanggal Pengajuan</td>
                    <td class="value">{{ $layanan->created_at->format('d F Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Selesai</td>
                    <td class="value">{{ $layanan->updated_at->format('d F Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Data Pemohon</div>
            <table class="info-grid">
                <tr>
                    <td class="label">Nama</td>
                    <td class="value">{{ $layanan->pengusul_nama }}</td>
                </tr>
                <tr>
                    <td class="label">NIM/NIP</td>
                    <td class="value">{{ $layanan->pengusul_nim ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Program Studi/Unit</td>
                    <td class="value">{{ $layanan->pengusul_prodi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="value">{{ $layanan->pengusul_email }}</td>
                </tr>
            </table>
        </div>

        @if(count($layanan->isians) > 0)
        <div class="section">
            <div class="section-title">Detail Kebutuhan</div>
            <table class="info-grid">
                @foreach($layanan->isians as $item)
                <tr>
                    <td class="label">{{ $item->isian->kategoriIsian->nama_isian ?? 'Data' }}</td>
                    <td class="value">{{ $item->nilai }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        <div class="qr-section">
            <div class="qr-box">
                <img src="data:image/png;base64,{{ $qrcode }}" alt="Verification QR Code">
                <p>Scan untuk validasi dokumen</p>
            </div>
        </div>

        <div class="footer">
            <p>Dokumen ini sah dan diterbitkan secara elektronik oleh sistem E-Office PCR.</p>
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
