@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Detail Kegiatan: {{ $Kegiatan->judul_Kegiatan }}" pretitle="Kegiatan">
    <x-slot:actions>
        <x-tabler.button href="{{ route('Kegiatan.Kegiatans.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
        <x-tabler.button href="{{ route('Kegiatan.Kegiatans.edit', $Kegiatan->encrypted_event_id) }}" class="btn-primary" icon="ti ti-edit" text="Edit Kegiatan" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Kegiatan</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small uppercase">Judul</label>
                            <div class="font-weight-medium">{{ $Kegiatan->judul_Kegiatan }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small uppercase">Jenis</label>
                            <div>{{ $Kegiatan->jenis_Kegiatan ?: '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small uppercase">Tanggal</label>
                            <div>
                                {{ formatTanggalIndo($Kegiatan->tanggal_mulai) }}
                                @if($Kegiatan->tanggal_selesai && $Kegiatan->tanggal_selesai != $Kegiatan->tanggal_mulai)
                                    - {{ formatTanggalIndo($Kegiatan->tanggal_selesai) }}
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small uppercase">Lokasi</label>
                            <div>{{ $Kegiatan->lokasi ?: '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small uppercase">PIC</label>
                            <div>{{ $Kegiatan->pic->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                            <li class="nav-item">
                                <a href="#tabs-deskripsi" class="nav-link active" data-bs-toggle="tab">Info Kegiatan</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tabs-panitia" class="nav-link" data-bs-toggle="tab">Tim</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tabs-tamu" class="nav-link" data-bs-toggle="tab">Tamu</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="tabs-deskripsi">
                                <div>
                                    {!! nl2br(e($Kegiatan->deskripsi)) ?: '<span class="text-muted fst-italic">Tidak ada deskripsi</span>' !!}
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-panitia">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="m-0">Anggota Tim</h4>
                                    <x-tabler.button class="btn-sm btn-primary ajax-modal-btn" data-modal-title="Tambah Anggota" data-url="{{ route('Kegiatan.teams.create', ['event_id' => $Kegiatan->encrypted_event_id]) }}" text="Tambah Anggota" />
                                </div>
                                <div class="card-table">
                                    <x-tabler.datatable-client
                                        id="table-tim"
                                        :columns="[
                                            ['name' => 'Nama'],
                                            ['name' => 'Jabatan/Peran'],
                                            ['name' => 'Status'],
                                            ['name' => '', 'className' => 'w-1', 'sortable' => false]
                                        ]"
                                    >
                                        @forelse($Kegiatan->teams as $team)
                                            <tr>
                                                <td>{{ $team->display_name }}</td>
                                                <td class="text-muted">{{ $team->role ?: '-' }}</td>
                                                <td>
                                                    @if($team->is_pic)
                                                        <span class="badge bg-purple-lt">PIC Utama</span>
                                                    @else
                                                        <span class="badge bg-blue-lt">Anggota</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-list flex-nowrap">
                                                        <x-tabler.button class="btn-icon btn-sm ajax-modal-btn" data-modal-title="Edit Anggota" data-url="{{ route('Kegiatan.teams.edit', $team->encrypted_eventteam_id) }}" icon="ti ti-edit" />
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            {{-- Handled by component --}}
                                        @endforelse
                                    </x-tabler.datatable-client>
                                    
                                    @if($Kegiatan->teams->isEmpty())
                                        <div class="text-center text-muted p-3">Belum ada anggota tim</div>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-tamu">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="m-0">Buku Tamu</h4>
                                    <x-tabler.button class="btn-sm btn-primary ajax-modal-btn" data-modal-title="Tambah Tamu" data-url="{{ route('Kegiatan.tamus.create', ['event_id' => $Kegiatan->encrypted_event_id]) }}" text="Tambah Tamu" />
                                </div>
                                <div class="card-table">
                                    <x-tabler.datatable-client
                                        id="table-tamu"
                                        :columns="[
                                            ['name' => 'Foto', 'className' => 'w-1', 'sortable' => false],
                                            ['name' => 'Nama'],
                                            ['name' => 'Instansi'],
                                            ['name' => 'Waktu Datang'],
                                            ['name' => '', 'className' => 'w-1', 'sortable' => false]
                                        ]"
                                    >
                                        @forelse($Kegiatan->tamus as $tamu)
                                            <tr>
                                                <td>
                                                    @if($tamu->photo_url)
                                                        <img src="{{ $tamu->photo_url }}" class="avatar avatar-sm" />
                                                    @else
                                                        <span class="avatar avatar-sm">?</span>
                                                    @endif
                                                </td>
                                                <td>{{ $tamu->nama_tamu }}</td>
                                                <td class="text-muted">{{ $tamu->instansi ?: '-' }}</td>
                                                <td class="text-muted">{{ $tamu->waktu_datang?->format('H:i') ?: '-' }}</td>
                                                <td>
                                                    <div class="btn-list flex-nowrap">
                                                        <x-tabler.button class="btn-icon btn-sm ajax-modal-btn" data-modal-title="Edit Tamu" data-url="{{ route('Kegiatan.tamus.edit', $tamu->encrypted_eventtamu_id) }}" icon="ti ti-edit" />
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            {{-- Handled by component --}}
                                        @endforelse
                                    </x-tabler.datatable-client>
                                    
                                    @if($Kegiatan->tamus->isEmpty())
                                        <div class="text-center text-muted p-3">Belum ada tamu terdaftar</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
