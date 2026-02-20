@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Detail Pendaftaran: {{ $pendaftaran->no_pendaftaran }}" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button type="button" class="btn-primary ajax-modal-btn" icon="ti ti-settings" text="Ubah Status" 
            data-modal-target="#modalAction" data-modal-title="Ubah Status Pendaftaran" data-url="{{ route('pmb.pendaftaran.update-status-form', $pendaftaran->encrypted_pendaftaran_id) }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

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
                    <div class="card-table">
                        <x-tabler.datatable-client
                            id="table-documents"
                            :columns="[
                                ['name' => 'Jenis Dokumen'],
                                ['name' => 'Status'],
                                ['name' => 'Aksi', 'className' => 'w-10']
                            ]"
                        >
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
                                        <x-tabler.button href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn-sm btn-info" text="Lihat" />
                                        <x-tabler.button type="button" class="btn-sm btn-success ajax-modal-btn" text="Verifikasi" 
                                            data-modal-target="#modalAction" data-modal-title="Verifikasi Dokumen" data-url="{{ route('pmb.pendaftaran.verify-document-form', $doc->encrypted_dokumenupload_id) }}" />
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </x-tabler.datatable-client>
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
