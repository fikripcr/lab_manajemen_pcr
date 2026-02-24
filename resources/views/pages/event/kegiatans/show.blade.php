@extends('layouts.tabler.app')

@section('title', 'Detail Kegiatan: ' . $event->judul_Kegiatan)

@section('header')
<x-tabler.page-header
    title="{{ $event->judul_Kegiatan }}"
    pretitle="Detail Kegiatan"
>
    <x-slot:actions>
        <x-tabler.button
            href="{{ route('Kegiatan.Kegiatans.index') }}"
            class="btn-secondary"
            icon="ti ti-arrow-left"
            text="Kembali"
        />
        <x-tabler.button
            href="{{ route('Kegiatan.Kegiatans.edit', $event->encrypted_event_id) }}"
            class="btn-primary"
            icon="ti ti-edit"
            text="Edit Kegiatan"
        />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row g-4">
    {{-- Left Column - Event Info --}}
    <div class="col-lg-4">
        {{-- Event Card --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary-lt">
                <h3 class="card-title text-primary">
                    <i class="ti ti-info-circle me-2"></i>Informasi Kegiatan
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">
                        <i class="ti ti-book me-1"></i>Jenis Kegiatan
                    </label>
                    <div class="fs-5 fw-semibold">{{ $event->jenis_Kegiatan ?: '-' }}</div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">
                        <i class="ti ti-calendar me-1"></i>Tanggal
                    </label>
                    <div class="fs-5">
                        @if($event->tanggal_mulai)
                            {{ formatTanggalIndo($event->tanggal_mulai) }}
                            @if($event->tanggal_selesai && $event->tanggal_selesai != $event->tanggal_mulai)
                                <br>
                                <span class="text-muted">s/d</span> {{ formatTanggalIndo($event->tanggal_selesai) }}
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">
                        <i class="ti ti-map-pin me-1"></i>Lokasi
                    </label>
                    <div class="fs-5">{{ $event->lokasi ?: '-' }}</div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">
                        <i class="ti ti-user-check me-1"></i>PIC Kegiatan
                    </label>
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar avatar-sm bg-primary-lt text-primary">
                            {{ substr($event->pic->name ?? '?', 0, 1) }}
                        </span>
                        <div class="fs-5">{{ $event->pic->name ?? '-' }}</div>
                    </div>
                </div>

                @php
                    $picLapangan = $event->teams->where('is_pic', true)->first();
                @endphp
                @if($picLapangan)
                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">
                        <i class="ti ti-star me-1"></i>PIC Utama di Lapangan
                    </label>
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar avatar-sm bg-purple-lt text-purple">
                            {{ substr($picLapangan->memberable->nama_pegawai ?? '?', 0, 1) }}
                        </span>
                        <div>
                            <div class="fs-5 fw-semibold">{{ $picLapangan->memberable->nama_pegawai ?? '-' }}</div>
                            <small class="text-muted">{{ $picLapangan->jabatan_dalam_tim ?? '' }}</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer text-muted small">
                <i class="ti ti-clock me-1"></i>
                Dibuat {{ $event->created_at ? $event->created_at->diffForHumans() : '-' }}
            </div>
        </div>
    </div>

    {{-- Right Column - Tabs --}}
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tabs-deskripsi" class="nav-link active" data-bs-toggle="tab">
                            <i class="ti ti-file-text me-2"></i>Info Kegiatan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-panitia" class="nav-link" data-bs-toggle="tab">
                            <i class="ti ti-users me-2"></i>Tim Pelaksana
                            <span class="badge bg-primary-lt text-primary ms-2">{{ $event->teams->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-tamu" class="nav-link" data-bs-toggle="tab">
                            <i class="ti ti-user-plus me-2"></i>Buku Tamu
                            <span class="badge bg-success-lt text-success ms-2">{{ $event->tamus->count() }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- Tab: Info Kegiatan --}}
                    <div class="tab-pane active show" id="tabs-deskripsi">
                        <div class="mb-3">
                            <h4 class="mb-3">
                                <i class="ti ti-article me-2 text-primary"></i>Deskripsi Kegiatan
                            </h4>
                            <div class="bg-light rounded p-4">
                                {!! nl2br(e($event->deskripsi)) ?: '<span class="text-muted fst-italic">Tidak ada deskripsi kegiatan</span>' !!}
                            </div>
                        </div>

                        @if($event->teams->where('is_pic', true)->first())
                        <div class="alert alert-warning">
                            <i class="ti ti-alert-triangle me-2"></i>
                            <strong>PIC Utama:</strong> {{ $event->teams->where('is_pic', true)->first().memberable.nama_pegawai }}
                            bertanggung jawab sebagai koordinator lapangan untuk kegiatan ini.
                        </div>
                        @endif
                    </div>

                    {{-- Tab: Tim Pelaksana --}}
                    <div class="tab-pane" id="tabs-panitia">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="m-0">
                                <i class="ti ti-users me-2 text-primary"></i>Anggota Tim Pelaksana
                            </h4>
                            <x-tabler.button
                                class="btn-sm btn-primary ajax-modal-btn"
                                data-modal-title="Tambah Anggota Tim"
                                data-url="{{ route('Kegiatan.Kegiatans.teams.create', $event->encrypted_event_id) }}"
                                icon="ti ti-user-plus"
                                text="Tambah Anggota"
                            />
                        </div>

                        <div class="card-table">
                            <x-tabler.datatable-client
                                id="table-tim"
                                :columns="[
                                    ['name' => 'Foto'],
                                    ['name' => 'Nama Pegawai'],
                                    ['name' => 'Jabatan/Peran'],
                                    ['name' => 'Status'],
                                    ['name' => '', 'class' => 'w-1', 'sortable' => false]
                                ]"
                            >
                                @forelse($event->teams->load('memberable') as $team)
                                    <tr>
                                        <td>
                                            @if($team->memberable.foto)
                                                <img src="{{ $team->memberable->foto }}" class="avatar avatar-sm rounded-circle" />
                                            @else
                                                <span class="avatar avatar-sm bg-primary-lt text-primary rounded-circle">
                                                    {{ substr($team->memberable.nama_pegawai ?? '?', 0, 1) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $team->memberable.nama_pegawai ?? '-' }}</div>
                                            <small class="text-muted">{{ $team->memberable.nip ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $team->role ?: '-' }}</div>
                                            <small class="text-muted">{{ $team->jabatan_dalam_tim ?? '' }}</small>
                                        </td>
                                        <td>
                                            @if($team->is_pic)
                                                <span class="badge bg-purple-lt text-purple">
                                                    <i class="ti ti-star me-1"></i>PIC Utama
                                                </span>
                                            @else
                                                <span class="badge bg-blue-lt text-blue">
                                                    <i class="ti ti-user me-1"></i>Anggota
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <x-tabler.button
                                                    class="btn-icon btn-sm ajax-modal-btn"
                                                    data-modal-title="Edit Anggota"
                                                    data-url="{{ route('Kegiatan.Kegiatans.teams.edit', ['event' => $event->encrypted_event_id, 'team' => $team->encrypted_eventteam_id]) }}"
                                                    icon="ti ti-edit"
                                                />
                                                <x-tabler.button
                                                    class="btn-icon btn-sm btn-danger ajax-delete"
                                                    data-url="{{ route('Kegiatan.Kegiatans.teams.destroy', ['event' => $event->encrypted_event_id, 'team' => $team->encrypted_eventteam_id]) }}"
                                                    data-title="Hapus Anggota?"
                                                    data-text="Anggota akan dihapus dari tim kegiatan ini"
                                                    icon="ti ti-trash"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Handled by component --}}
                                @endforelse
                            </x-tabler.datatable-client>

                            @if($event->teams->isEmpty())
                                <div class="text-center text-muted p-5">
                                    <i class="ti ti-users-off" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0">Belum ada anggota tim</p>
                                    <small>Klik "Tambah Anggota" untuk menambahkan anggota tim</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tab: Buku Tamu --}}
                    <div class="tab-pane" id="tabs-tamu">
                        {{-- Buku Tamu Digital Card — Permanent Link --}}
                        @php $attendanceUrl = route('attendance.form', $event->encrypted_event_id) @endphp
                        <div class="card mb-4 border-2 border-success-subtle">
                            <div class="card-body">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <span class="avatar avatar-lg bg-success text-white">
                                            <i class="ti ti-qrcode fs-2"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h4 class="mb-0">Buku Tamu Digital</h4>
                                            <span class="badge bg-success-lt text-success">Aktif</span>
                                        </div>
                                        <div class="input-group input-group-sm" style="max-width:500px">
                                            <input type="text" class="form-control font-monospace text-muted"
                                                value="{{ $attendanceUrl }}" readonly id="attendance-url">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="navigator.clipboard.writeText('{{ $attendanceUrl }}').then(()=>{
                                                    this.innerHTML='<i class=\'ti ti-check\'></i> Tersalin';
                                                    setTimeout(()=>this.innerHTML='<i class=\'ti ti-copy\'></i> Salin',2000)
                                                })">
                                                <i class="ti ti-copy"></i> Salin
                                            </button>
                                            <a href="{{ $attendanceUrl }}" target="_blank" class="btn btn-outline-primary">
                                                <i class="ti ti-external-link"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="m-0">
                                <i class="ti ti-book me-2 text-success"></i>Daftar Tamu
                            </h4>
                            <x-tabler.button
                                class="btn-sm btn-success ajax-modal-btn"
                                data-modal-title="Tambah Tamu"
                                data-url="{{ route('Kegiatan.Kegiatans.tamus.create', $event->encrypted_event_id) }}"
                                icon="ti ti-user-plus"
                                text="Tambah Manual"
                            />
                        </div>
                        <div class="card-table">
                            <x-tabler.datatable-client
                                id="table-tamu"
                                :columns="[
                                    ['name' => 'Foto', 'class' => 'w-1', 'sortable' => false],
                                    ['name' => 'Nama'],
                                    ['name' => 'Instansi'],
                                    ['name' => 'No. HP'],
                                    ['name' => 'Waktu Datang'],
                                    ['name' => '', 'class' => 'w-1', 'sortable' => false]
                                ]"
                            >
                                @forelse($event->tamus as $tamu)
                                    <tr>
                                        <td>
                                            @if($tamu->photo_url)
                                                <img src="{{ $tamu->photo_url }}" class="avatar avatar-sm rounded-circle" />
                                            @else
                                                <span class="avatar avatar-sm bg-success-lt text-success rounded-circle">?</span>
                                            @endif
                                        </td>
                                        <td>{{ $tamu->nama_tamu }}</td>
                                        <td class="text-muted">{{ $tamu->instansi ?: '-' }}</td>
                                        <td class="text-muted">
                                            @if($tamu->kontak)
                                                <i class="ti ti-device-mobile me-1"></i>{{ $tamu->kontak }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ $tamu->waktu_datang ? \Carbon\Carbon::parse($tamu->waktu_datang)->format('H:i') : '-' }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <x-tabler.button
                                                    class=" ajax-modal-btn"
                                                    data-modal-title="Edit Tamu"
                                                    data-url="{{ route('Kegiatan.Kegiatans.tamus.edit', ['event' => $event->encrypted_event_id, 'tamu' => $tamu->encrypted_eventtamu_id]) }}"
                                                    icon="ti ti-edit"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Handled by component --}}
                                @endforelse
                            </x-tabler.datatable-client>

                            @if($event->tamus->isEmpty())
                                <div class="text-center text-muted p-5">
                                    <i class="ti ti-book-off" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0">Belum ada tamu terdaftar</p>
                                    <small>Klik "Tambah Tamu" untuk menambahkan tamu undangan</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
