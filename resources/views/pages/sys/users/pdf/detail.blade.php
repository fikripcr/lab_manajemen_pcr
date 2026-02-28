<!DOCTYPE html>
<html>
<head>
    <title>Detail Pengguna - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px 20px;
            margin-top: 10px;
        }
        .label {
            font-weight: bold;
            text-align: left;
        }
        .value {
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detail Pengguna</h1>
        <p>{{ $user->name }}</p>
        <p>Laporan Dibuat: {{ $reportDate }}</p>
    </div>

    <div class="info-section">
        <h3>Informasi Pengguna</h3>
        <div class="info-grid">
            <div class="label">ID:</div>
            <div class="value">{{ $user->id }}</div>
            
            <div class="label">Nama:</div>
            <div class="value">{{ $user->name }}</div>
            
            <div class="label">Email:</div>
            <div class="value">{{ $user->email }}</div>
            
            <div class="label">Role:</div>
            <div class="value">{{ $user->getRoleNames()->first() ?? 'Tidak ada role' }}</div>
            
            
            <div class="label">Tanggal Dibuat:</div>
            <div class="value">{{ formatTanggalIndo($user->created_at) }}</div>
            
            <div class="label">Tanggal Diubah:</div>
            <div class="value">{{ formatTanggalIndo($user->updated_at) }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem.</p>
        <p>Hak Cipta &copy; {{ date('Y') }} Sistem Laboratorium Digital.</p>
    </div>
</body>
</html>
