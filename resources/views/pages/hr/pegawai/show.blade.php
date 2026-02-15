@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
<x-tabler.page-header :title="$pegawai->nama" pretitle="Detail Pegawai">
    <x-slot:avatar>
        <span class="avatar avatar-md rounded" style="background-image: url({{ $pegawai->latestDataDiri->file_foto ? asset($pegawai->latestDataDiri->file_foto) : asset('static/avatars/000m.jpg') }})"></span>
    </x-slot:avatar>
    <x-slot:actions>
        <x-tabler.button :href="route('hr.pegawai.edit', encryptId($pegawai->pegawai_id))" icon="ti ti-edit" text="Ubah" />
        <x-tabler.button type="back" :href="route('hr.pegawai.index')" />
    </x-slot:actions>
    <div class="text-muted mt-1">
        NIP: {{ $pegawai->nip }} &bull; {{ $pegawai->email }}
    </div>
</x-tabler.page-header>
@endsection

@section('content')

@if($pendingChange)
<div class="alert alert-info mb-3 py-2">
    <div class="d-flex">
        <i class="ti ti-info-circle fs-2 me-2"></i>
        <div>
            <h4 class="alert-title mb-1">Menunggu Persetujuan Admin</h4>
            <div class="text-secondary small">Anda memiliki permohonan perubahan data yang sedang diproses. Harap menunggu persetujuan dari admin sebelum mengajukan perubahan lainnya.</div>
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
            <li class="nav-item">
                <a href="#tab-datadiri" class="nav-link active" data-bs-toggle="tab">
                    <i class="ti ti-user me-2"></i> Data Diri
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-kepegawaian" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-briefcase me-2"></i> Kepegawaian
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-pendidikan" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-school me-2"></i> Pendidikan
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-keluarga" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-users me-2"></i> Keluarga
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-pengembangan" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-certificate me-2"></i> Sertifikat/Pelatihan
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-files" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-file-text me-2"></i> File
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
                        <div class="card card-sm">
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
                        <div class="card card-sm">
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
                @include('pages.hr.data-diri.penugasan')
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
