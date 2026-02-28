<!DOCTYPE html>
<html>
<head>
    <title>User Report</title>
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
        .info {
            margin-bottom: 20px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        <h1>Laporan Pengguna</h1>
        <p>Sistem Laboratorium Digital</p>
        <p>Tanggal Laporan: {{ $reportDate }}</p>
    </div>

    <div class="info">
        <p>Jenis Laporan: {{ ucfirst($summaryType) }}</p>
        @if($filters['search'])
            <p>Cari: {{ $filters['search'] }}</p>
        @endif
        @if($filters['role'])
            <p>Peran: {{ $filters['role'] }}</p>
        @endif
        @if($filters['date_from'] || $filters['date_to'])
            <p>Tanggal:
                @if($filters['date_from'])
                    {{ formatTanggalIndo($filters['date_from']) }}
                @endif
                @if($filters['date_from'] && $filters['date_to'])
                    -
                @endif
                @if($filters['date_to'])
                    {{ formatTanggalIndo($filters['date_to']) }}
                @endif
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->first()?->name ?? 'No Role' }}</td>
                    <td>{{ formatTanggalIndo($user->created_at) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Jumlah Pengguna: {{ $users->count() }}</p>
        <p>Laporan ini dibuat secara otomatis oleh sistem.</p>
        <p>Hak Cipta &copy; {{ date('Y') }} Sistem Laboratorium Digital.</p>
    </div>
</body>
</html>
