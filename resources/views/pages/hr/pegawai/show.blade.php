@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

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
        <x-tabler.button href="{{ route('hr.pegawai.edit', encryptId($pegawai->pegawai_id)) }}" class="btn-primary" icon="ti ti-edit">
            Edit Profile
        </x-tabler.button>
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
            <li class="nav-item">
                <a href="#tab-files" class="nav-link" data-bs-toggle="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11v6" /><path d="M9 14l3 3l3 -3" /></svg>
                    File Pegawai
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
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Unit / Departemen</div>
                                        <div class="datagrid-content">{{ $pegawai->latestDataDiri->departemen->name ?? '-' }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Posisi</div>
                                        <div class="datagrid-content">{{ $pegawai->latestDataDiri->posisi->name ?? '-' }}</div>
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
                @include('pages.hr.pegawai.parts._status_pegawai_list')
                @include('pages.hr.pegawai.parts._jabatan_fungsional_list')
                @include('pages.hr.data-diri.penugasan') {{-- Using the Penugasan list view directly --}}
                @include('pages.hr.pegawai.parts._status_aktifitas_list')
                @include('pages.hr.pegawai.parts._inpassing_list')
            </div>

            <!-- Tab Pendidikan -->
            <div class="tab-pane" id="tab-pendidikan">
                @include('pages.hr.pegawai.parts._pendidikan_list')
            </div>

            <!-- Tab Keluarga -->
            <div class="tab-pane" id="tab-keluarga">
                @include('pages.hr.pegawai.parts._keluarga_list')
            </div>

            <!-- Tab Inpassing -->
            <div class="tab-pane" id="tab-inpassing">
                @include('pages.hr.pegawai.parts._inpassing_list')
            </div>

            <!-- Tab Pengembangan Diri -->
            <div class="tab-pane" id="tab-pengembangan">
                @include('pages.hr.pegawai.parts._pengembangan_list')
            </div>

            <!-- Tab File Pegawai -->
            <div class="tab-pane" id="tab-files">
                @include('pages.hr.pegawai.parts._file_list')
            </div>

        </div>
    </div>
</div>
@endsection
