@extends('layouts.admin.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col-auto">
        <span class="avatar avatar-lg rounded" style="background-image: url({{ $pegawai->latestDataDiri->file_foto ? asset($pegawai->latestDataDiri->file_foto) : asset('static/avatars/000m.jpg') }})"></span>
    </div>
    <div class="col">
        <div class="page-pretitle">Detail Pegawai</div>
        <h2 class="page-title">
            {{ $pegawai->nama }}
        </h2>
        <div class="text-muted">NIP: {{ $pegawai->nip }} &bull; {{ $pegawai->email }}</div>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <a href="{{ route('hr.pegawai.edit', $pegawai->pegawai_id) }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
            Edit Profile
        </a>
    </div>
</div>
@endsection

@section('content')

@if($pendingChange)
<div class="alert alert-info mb-3">
    <div class="d-flex">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
        </div>
        <div>
            <h4 class="alert-title">Menunggu Persetujuan Admin</h4>
            <div class="text-secondary">Anda memiliki permohonan perubahan data yang sedang diproses. Harap menunggu persetujuan dari admin sebelum mengajukan perubahan lainnya.</div>
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
            <li class="nav-item">
                <a href="#tab-datadiri" class="nav-link active" data-bs-toggle="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                    Data Diri
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-kepegawaian" class="nav-link" data-bs-toggle="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                    Kepegawaian
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-pendidikan" class="nav-link" data-bs-toggle="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" /></svg>
                    Pendidikan
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-keluarga" class="nav-link" data-bs-toggle="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m0 4a4 4 0 0 1 4 -4h2a4 4 0 0 1 4 4v2" /><path d="M16 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M9 7a4 4 0 0 1 4 4v2" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                    Keluarga
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-pengembangan" class="nav-link" data-bs-toggle="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 15l8.385 -5.315c.915 -.58 .96 -2.001 .009 -2.592l-8.394 -5.093l-8.394 5.093c-.96 .603 -.915 2.024 .01 2.592l8.384 5.315" /><path d="M5 12l-1.6 1.013c-.95 .603 -.905 2.025 .02 2.593l8.58 5.394l8.58 -5.394c.925 -.56 .97 -1.98 .02 -2.593l-1.6 -1.013" /></svg>
                    Pengembangan Diri
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            
            <!-- Tab Data Diri -->
            <div class="tab-pane active show" id="tab-datadiri">
                @if($pendingChange)
                <div class="card mb-3 border-info">
                    <div class="card-header">
                        <h3 class="card-title text-info">Perbandingan Perubahan Data</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Data Saat Ini</th>
                                    <th>Data Pengajuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Nama Lengkap</td>
                                    <td>{{ $pegawai->latestDataDiri->nama }}</td>
                                    <td class="{{ $pegawai->latestDataDiri->nama !== $pendingChange->nama ? 'text-danger fw-bold' : '' }}">{{ $pendingChange->nama }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>{{ $pegawai->latestDataDiri->alamat }}</td>
                                    <td class="{{ $pegawai->latestDataDiri->alamat !== $pendingChange->alamat ? 'text-danger fw-bold' : '' }}">{{ $pendingChange->alamat }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div class="row row-cards">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Pribadi</h3>
                            </div>
                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Nama Lengkap</div>
                                        <div class="datagrid-content">{{ $pegawai->nama }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Inisial</div>
                                        <div class="datagrid-content">{{ $pegawai->inisial }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Tempat, Tanggal Lahir</div>
                                        <div class="datagrid-content">{{ $pegawai->latestDataDiri->tempat_lahir ?? '-' }}, {{ optional($pegawai->latestDataDiri->tgl_lahir)->format('d F Y') }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Jenis Kelamin</div>
                                        <div class="datagrid-content">{{ $pegawai->latestDataDiri->jenis_kelamin ?? '-' }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Status Pernikahan</div>
                                        <div class="datagrid-content">{{ $pegawai->latestDataDiri->status_nikah ?? '-' }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Agama</div>
                                        <div class="datagrid-content">{{ $pegawai->latestDataDiri->agama ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Kontak & Identitas</h3>
                            </div>
                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">NIP / NIK</div>
                                        <div class="datagrid-content">{{ $pegawai->nip }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">NIDN</div>
                                        <div class="datagrid-content">{{ $pegawai->latestDataDiri->nidn ?? '-' }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Email</div>
                                        <div class="datagrid-content">{{ $pegawai->email }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">No. HP</div>
                                        <div class="datagrid-content">{{ $pegawai->latestDataDiri->no_hp ?? '-' }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Alamat</div>
                                        <div class="datagrid-content">{{ $pegawai->latestDataDiri->alamat ?? '-' }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">NPWP</div>
                                        <div class="datagrid-content">{{ $pegawai->latestDataDiri->npwp ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Kepegawaian -->
             <div class="tab-pane" id="tab-kepegawaian">
                
                {{-- 1. Riwayat Status Pegawai --}}
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    <h3>Riwayat Status Pegawai</h3>
                    <a href="{{ route('hr.pegawai.status-pegawai.create', $pegawai->pegawai_id) }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Ubah Status
                    </a>
                </div>
                <div class="card mb-3">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>TMT</th>
                                    <th>No. SK</th>
                                    <th>File SK</th>
                                    <th>Status Approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pegawai->historyStatPegawai as $item)
                                <tr>
                                    <td><span class="badge bg-blue text-blue-fg">{{ $item->statusPegawai->nama_status ?? '-' }}</span></td>
                                    <td>{{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}</td>
                                    <td>{{ $item->no_sk ?? '-' }}</td>
                                    <td>
                                        @if($item->file_sk)
                                            <a href="#" target="_blank">Lihat</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Determine approval status (generic logic since created_by/approval might differ) --}}
                                        {{-- For now, if it's the latest confirmed, it's Active. If pending request exists matching this, it's Pending. --}}
                                        {{-- Simplification: check if this record is linked to a pending approval or is the current active one --}}
                                        @if($pegawai->latest_riwayatstatpegawai_id == $item->riwayatstatpegawai_id)
                                            <span class="badge bg-success">Aktif Saat Ini</span>
                                        @else
                                            <span class="badge bg-secondary">Riwayat</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada data riwayat status</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 2. Riwayat Jabatan Fungsional --}}
                <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                    <h3>Riwayat Jabatan Fungsional</h3>
                    <a href="{{ route('hr.pegawai.jabatan-fungsional.create', $pegawai->pegawai_id) }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Ubah Jafung
                    </a>
                </div>
                <div class="card mb-3">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Jabatan</th>
                                    <th>TMT</th>
                                    <th>No. SK</th>
                                    <th>Status Approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pegawai->historyJabFungsional as $item)
                                <tr>
                                    <td>{{ $item->jabatanFungsional->jabfungsional ?? '-' }}</td>
                                    <td>{{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}</td>
                                    <td>{{ $item->no_sk_internal ?? $item->no_sk_kopertis ?? '-' }}</td>
                                    <td>
                                        @if($pegawai->latest_riwayatjabfungsional_id == $item->riwayatjabfungsional_id)
                                            <span class="badge bg-success">Aktif Saat Ini</span>
                                        @else
                                            <span class="badge bg-secondary">Riwayat</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted">Belum ada data riwayat jabatan fungsional</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                 {{-- 3. Riwayat Jabatan Struktural --}}
                 <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                    <h3>Riwayat Jabatan Struktural</h3>
                    <a href="{{ route('hr.pegawai.jabatan-struktural.create', $pegawai->pegawai_id) }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Ubah Struktural
                    </a>
                </div>
                <div class="card mb-3">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Jabatan</th>
                                    <th>Tgl Mulai</th>
                                    <th>Tgl Selesai</th>
                                    <th>No. SK</th>
                                    <th>Status Approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Assuming historyJabStruktural relation exists --}}
                                @if($pegawai->historyJabStruktural)
                                    @forelse($pegawai->historyJabStruktural as $item)
                                    <tr>
                                        <td>{{ $item->jabatanStruktural->jabstruktural ?? '-' }}</td>
                                        <td>{{ $item->tgl_awal ? $item->tgl_awal->format('d F Y') : '-' }}</td>
                                        <td>{{ $item->tgl_akhir ? $item->tgl_akhir->format('d F Y') : '-' }}</td>
                                        <td>{{ $item->no_sk ?? '-' }}</td>
                                        <td>
                                            @if($pegawai->latest_riwayatjabstruktural_id == $item->riwayatjabstruktural_id)
                                                <span class="badge bg-success">Aktif Saat Ini</span>
                                            @else
                                                <span class="badge bg-secondary">Riwayat</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center text-muted">Belum ada data riwayat jabatan struktural</td></tr>
                                    @endforelse
                                @else
                                     <tr><td colspan="5" class="text-center text-muted">Relation historyJabStruktural not loaded or defined</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 4. Riwayat Status Aktifitas --}}
                <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                    <h3>Riwayat Status Aktifitas</h3>
                     <a href="{{ route('hr.pegawai.status-aktifitas.create', $pegawai->pegawai_id) }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Ubah Status Aktifitas
                    </a>
                </div>
                <div class="card mb-3">
                     <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>TMT</th>
                                    <th>Tgl Akhir</th>
                                    <th>Status Approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pegawai->historyStatAktifitas as $item)
                                <tr>
                                    <td>{{ $item->statusAktifitas->nama_status ?? '-' }}</td>
                                    <td>{{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}</td>
                                    <td>{{ $item->tgl_akhir ? $item->tgl_akhir->format('d F Y') : '-' }}</td>
                                    <td>
                                        @if($pegawai->latest_riwayatstataktifitas_id == $item->riwayatstataktifitas_id)
                                            <span class="badge bg-success">Aktif Saat Ini</span>
                                        @else
                                            <span class="badge bg-secondary">Riwayat</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted">Belum ada data riwayat status aktifitas</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Pendidikan -->
            <div class="tab-pane" id="tab-pendidikan">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Riwayat Pendidikan</h3>
                    <a href="{{ route('hr.pegawai.pendidikan.create', $pegawai->pegawai_id) }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Tambah Pendidikan
                    </a>
                </div>
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Jenjang</th>
                                    <th>Nama PT</th>
                                    <th>Bidang Ilmu</th>
                                    <th>Tahun Lulus</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pegawai->riwayatPendidikan as $edu)
                                <tr>
                                    <td>{{ $edu->jenjang_pendidikan }}</td>
                                    <td>{{ $edu->nama_pt }}</td>
                                    <td>{{ $edu->bidang_ilmu }}</td>
                                    <td>{{ $edu->tgl_ijazah ? $edu->tgl_ijazah->format('Y') : '-' }}</td>
                                    <td>
                                        @if($edu->approval && $edu->approval->status == 'Pending')
                                            <span class="badge bg-warning">Menunggu Approval</span>
                                        @elseif($edu->approval && $edu->approval->status == 'Approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($edu->file_ijazah)
                                        <a href="#" class="btn btn-ghost-secondary btn-sm btn-icon" aria-label="Download">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted">Belum ada data pendidikan</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Keluarga -->
            <div class="tab-pane" id="tab-keluarga">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Data Keluarga</h3>
                    <a href="{{ route('hr.pegawai.keluarga.create', $pegawai->pegawai_id) }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Tambah Anggota Keluarga
                    </a>
                </div>
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Hubungan</th>
                                    <th>L/P</th>
                                    <th>Tgl Lahir</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pegawai->keluarga as $kel)
                                <tr>
                                    <td>{{ $kel->nama }}</td>
                                    <td>{{ $kel->hubungan }}</td>
                                    <td>{{ $kel->jenis_kelamin }}</td>
                                    <td>{{ $kel->tgl_lahir ? \Carbon\Carbon::parse($kel->tgl_lahir)->format('d-m-Y') : '-' }}</td>
                                    <td>
                                        @if($kel->approval && $kel->approval->status == 'Pending')
                                            <span class="badge bg-warning">Menunggu Approval</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada data keluarga</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Pengembangan Diri -->
            <div class="tab-pane" id="tab-pengembangan">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Pengembangan Diri</h3>
                    <a href="{{ route('hr.pegawai.pengembangan.create', $pegawai->pegawai_id) }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Tambah Kegiatan
                    </a>
                </div>
                <div class="card">
                     <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Jenis Kegiatan</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Penyelenggara</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pegawai->pengembanganDiri as $dev)
                                <tr>
                                    <td>{{ $dev->jenis_kegiatan }}</td>
                                    <td>{{ $dev->nama_kegiatan }}</td>
                                    <td>{{ $dev->penyelenggara }}</td>
                                    <td>{{ $dev->tahun }}</td>
                                    <td>
                                        @if($dev->approval && $dev->approval->status == 'Pending')
                                            <span class="badge bg-warning">Menunggu Approval</span>
                                        @else
                                            <span class="badge bg-success">Disetujui</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada data pengembangan diri</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
