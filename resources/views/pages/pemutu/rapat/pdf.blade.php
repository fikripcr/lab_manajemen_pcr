<!DOCTYPE html>
<html>
<head>
    <title>Hasil Rapat - {{ $rapat->judul_kegiatan }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14pt;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .meta-table td {
            padding: 5px;
            vertical-align: top;
        }
        .meta-label {
            font-weight: bold;
            width: 150px;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
            padding-bottom: 5px;
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .table th, .table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8pt;
            color: #fff;
        }
        .bg-success { background-color: #2fb344; }
        .bg-warning { background-color: #f76707; }
        .bg-danger { background-color: #d63939; }
        .bg-secondary { background-color: #6c757d; }
        .agenda-item {
            margin-bottom: 15px;
        }
        .agenda-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .agenda-content {
            padding-left: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Hasil Rapat</h1>
        <h2>{{ $rapat->judul_kegiatan }}</h2>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Jenis Rapat</td>
            <td>: {{ $rapat->jenis_rapat }}</td>
        </tr>
        <tr>
            <td class="meta-label">Tanggal</td>
            <td>: {{ formatTanggalIndo($rapat->tgl_rapat) }}</td>
        </tr>
        <tr>
            <td class="meta-label">Waktu</td>
            <td>: {{ $rapat->waktu_mulai->format('H:i') }} - {{ $rapat->waktu_selesai->format('H:i') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Tempat</td>
            <td>: {{ $rapat->tempat_rapat }}</td>
        </tr>
        <tr>
            <td class="meta-label">Ketua Rapat</td>
            <td>: {{ $rapat->ketuaUser->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Notulen</td>
            <td>: {{ $rapat->notulenUser->name ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Daftar Hadir Peserta</div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 35%">Nama</th>
                <th style="width: 25%">Jabatan</th>
                <th style="width: 15%">Status</th>
                <th style="width: 20%">Waktu Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rapat->pesertas as $index => $peserta)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $peserta->user->name ?? '-' }}</td>
                <td>{{ $peserta->jabatan }}</td>
                <td>
                    @if($peserta->status == 'hadir')
                        Hadir
                    @elseif($peserta->status == 'izin')
                        Izin
                    @elseif($peserta->status == 'sakit')
                        Sakit
                    @elseif($peserta->status == 'alpa')
                        Alpa
                    @else
                        -
                    @endif
                </td>
                <td>{{ $peserta->waktu_hadir ? $peserta->waktu_hadir->format('H:i') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Pembahasan Agenda</div>
    @foreach($rapat->agendas as $index => $agenda)
        <div class="agenda-item">
            <div class="agenda-title">{{ $index + 1 }}. {{ $agenda->judul_agenda }}</div>
            <div class="agenda-content">
                {!! $agenda->isi ?? '<em>Belum ada catatan pembahasan.</em>' !!}
            </div>
        </div>
    @endforeach

    @if($rapat->entitas->count() > 0)
        <div class="section-title">Entitas Terkait</div>
        <ul>
            @foreach($rapat->entitas as $entitas)
                <li>
                    <strong>{{ $entitas->model }}</strong>: {{ $entitas->model_id }} <br>
                    <small>Keterangan: {{ $entitas->keterangan ?? '-' }}</small>
                </li>
            @endforeach
        </ul>
    @endif

</body>
</html>
