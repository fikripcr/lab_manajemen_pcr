@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.tabler.empty' : 'layouts.tabler.app')

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

<div class="row">
    {{-- Sidebar Navigation --}}
    <div class="col-md-3 col-lg-2">
        <div class="card sticky-top">
            <div class="list-group list-group-flush">
                <a href="#section-datadiri" class="list-group-item list-group-item-action active" data-section="datadiri">
                    <i class="ti ti-user me-2"></i> Data Diri
                </a>
                <a href="#section-kepegawaian" class="list-group-item list-group-item-action" data-section="kepegawaian">
                    <i class="ti ti-briefcase me-2"></i> Kepegawaian
                </a>
                <a href="#section-pendidikan" class="list-group-item list-group-item-action" data-section="pendidikan">
                    <i class="ti ti-school me-2"></i> Pendidikan
                </a>
                <a href="#section-keluarga" class="list-group-item list-group-item-action" data-section="keluarga">
                    <i class="ti ti-users me-2"></i> Keluarga
                </a>
                <a href="#section-pengembangan" class="list-group-item list-group-item-action" data-section="pengembangan">
                    <i class="ti ti-certificate me-2"></i> Sertifikat/Pelatihan
                </a>
                <a href="#section-files" class="list-group-item list-group-item-action" data-section="files">
                    <i class="ti ti-file-text me-2"></i> File
                </a>
            </div>
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="col-md-9 col-lg-10">
        
        {{-- Section: Data Diri --}}
        <div id="section-datadiri" class="content-section">
            <h2 class="mb-3">Data Diri</h2>
            
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
        </div>

        {{-- Section: Kepegawaian --}}
        <div id="section-kepegawaian" class="content-section">
            <h2 class="mb-3">Kepegawaian</h2>
            @include('pages.hr.pegawai.parts._status_pegawai_list')
            @include('pages.hr.pegawai.parts._jabatan_fungsional_list')
            @include('pages.hr.data-diri.penugasan')
            @include('pages.hr.pegawai.parts._status_aktifitas_list')
            @include('pages.hr.pegawai.parts._inpassing_list')
        </div>

        {{-- Section: Pendidikan --}}
        <div id="section-pendidikan" class="content-section">
            <h2 class="mb-3">Pendidikan</h2>
            @include('pages.hr.pegawai.parts._pendidikan_list')
        </div>

        {{-- Section: Keluarga --}}
        <div id="section-keluarga" class="content-section">
            <h2 class="mb-3">Keluarga</h2>
            @include('pages.hr.pegawai.parts._keluarga_list')
        </div>

        {{-- Section: Pengembangan Diri --}}
        <div id="section-pengembangan" class="content-section">
            <h2 class="mb-3">Sertifikat/Pelatihan</h2>
            @include('pages.hr.pegawai.parts._pengembangan_list')
        </div>

        {{-- Section: File Pegawai --}}
        <div id="section-files" class="content-section">
            <h2 class="mb-3">File</h2>
            @include('pages.hr.pegawai.parts._file_list')
        </div>

    </div>
</div>

@push('scripts')
<script>
// Tab-like behavior: Show/hide sections
document.querySelectorAll('.list-group-item-action').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Get target section
        const targetId = this.getAttribute('href');
        const targetSection = document.querySelector(targetId);
        
        // Hide all sections
        document.querySelectorAll('.content-section').forEach(section => {
            section.style.display = 'none';
        });
        
        // Show target section
        if (targetSection) {
            targetSection.style.display = 'block';
            
            // Scroll to top of content area smoothly
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
        
        // Update active state in sidebar
        document.querySelectorAll('.list-group-item-action').forEach(item => {
            item.classList.remove('active');
        });
        this.classList.add('active');
    });
});

// Initialize: Show only first section (Data Diri)
document.addEventListener('DOMContentLoaded', function() {
    // Hide all sections except first
    document.querySelectorAll('.content-section').forEach((section, index) => {
        if (index === 0) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
