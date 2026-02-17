@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Detail Pendaftaran: {{ $pendaftaran->no_pendaftaran }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Ubah Status Pendaftaran" data-url="{{ route('pmb.pendaftaran.update-status-form', $pendaftaran->encrypted_id) }}">
                    <i class="ti ti-settings"></i> Ubah Status
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            {{-- Profile Info --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Profil Calon Mahasiswa</h3></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 text-center">
                                <span class="avatar avatar-xl rounded" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($pendaftaran->user->name) }})"></span>
                            </div>
                            <div class="col-12">
                                <label class="form-label mb-1">Nama Lengkap</label>
                                <div class="form-control-plaintext">{{ $pendaftaran->user->name }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label mb-1">NIK</label>
                                <div class="form-control-plaintext">{{ $pendaftaran->profil->nik ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label mb-1">Asal Sekolah</label>
                                <div class="form-control-plaintext">{{ $pendaftaran->profil->asal_sekolah ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Documents --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Berkas Persyaratan</h3></div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Jenis Dokumen</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendaftaran->dokumenUpload as $doc)
                                <tr>
                                    <td>{{ $doc->jenisDokumen->nama_dokumen }}</td>
                                    <td>
                                        @if($doc->is_verified)
                                            <span class="badge bg-success text-white">Terverifikasi</span>
                                        @else
                                            <span class="badge bg-warning text-white">Belum Dicek</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                            <button type="button" class="btn btn-sm btn-success ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Verifikasi Dokumen" data-url="{{ route('pmb.pendaftaran.verify-document-form', $doc->encrypted_id) }}">Verifikasi</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- History --}}
                <div class="card mt-3">
                    <div class="card-header"><h3 class="card-title">Riwayat Status</h3></div>
                    <div class="card-body">
                        <ul class="list list-timeline">
                            @foreach($pendaftaran->riwayat as $r)
                            <li>
                                <div class="list-timeline-icon bg-primary"></div>
                                <div class="list-timeline-content">
                                    <div class="list-timeline-time">{{ formatTanggalIndo($r->waktu_kejadian) }}</div>
                                    <p class="list-timeline-title">{{ str_replace('_', ' ', $r->status_baru) }}</p>
                                    <p class="text-muted">{{ $r->keterangan }} (Oleh: {{ $r->pelaku->name ?? 'System' }})</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
