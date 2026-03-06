@extends('layouts.tabler.app')
@section('title', 'RTM Peningkatan — Periode ' . $periode->periode)

@section('header')
@php
    $pretitle = 'Periode ' . $periode->periode . ' · ' . $periode->jenis_periode;
    if ($periode->peningkatan_awal && $periode->peningkatan_akhir) {
        $pretitle .= ' · ' . formatTanggalIndo($periode->peningkatan_awal) . ' s/d ' . formatTanggalIndo($periode->peningkatan_akhir);
    }
@endphp
<x-tabler.page-header title="RTM Peningkatan" :pretitle="$pretitle">
    <x-slot:actions>
        <x-tabler.button type="back" href="{{ route('pemutu.peningkatan.index') }}" size="sm" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row">

    {{-- Tab Navigation --}}
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body p-2">
                <ul class="nav nav-pills" id="peningkatan-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#section-rtm" class="nav-link active" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-calendar-event me-2"></i> Data Umum & Agenda
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#section-duplikasi" class="nav-link" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-copy me-2"></i> Tahap 1-Duplikasi Standar
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#section-manage" class="nav-link" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-settings-2 me-2"></i> Tahap 2-Manage Indikator
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="tab-content" id="peningkatan-tab-content">

        {{-- ===== SECTION: RTM (Data Umum & Agenda) ===== --}}
        <div id="section-rtm" class="tab-pane fade show active" role="tabpanel">

            @if(!$rapat)
                {{-- RTM Belum Ada --}}
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <span class="avatar avatar-xl rounded bg-blue-lt">
                                <i class="ti ti-calendar-plus fs-1"></i>
                            </span>
                        </div>
                        <h3>Belum Ada RTM</h3>
                        <p class="text-muted">Buat Rapat Tinjauan Manajemen untuk memulai proses peningkatan periode ini.</p>
                        <x-tabler.button type="button" class="btn-primary ajax-modal-btn"
                            data-url="{{ route('pemutu.peningkatan.rtm.create', $periode->encrypted_periodespmi_id) }}"
                            data-modal-title="Buat RTM Peningkatan"
                            icon="ti ti-plus" text="Buat RTM" />
                    </div>
                </div>
            @else
                {{-- RTM Sudah Ada — Two-column layout --}}
                <div class="row row-cards">

                    {{-- ══ KIRI: Tabs Data Umum + Peserta ══ --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                                    <li class="nav-item">
                                        <a href="#rtm-tab-umum" class="nav-link active" data-bs-toggle="tab">
                                            <i class="ti ti-info-circle me-1"></i> Data Umum
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#rtm-tab-peserta" class="nav-link" data-bs-toggle="tab">
                                            <i class="ti ti-users me-1"></i> Peserta
                                            <span class="badge bg-green-lt ms-1">{{ $rapat->pesertas->count() }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">

                                    {{-- ── TAB: Data Umum ── --}}
                                    <div class="tab-pane active show" id="rtm-tab-umum">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h3 class="mb-0"><i class="ti ti-calendar-event me-2 text-blue"></i>Info Rapat</h3>
                                            <x-tabler.button type="button" class="btn-outline-primary btn-sm ajax-modal-btn"
                                                data-url="{{ route('pemutu.peningkatan.rtm.edit', [$periode->encrypted_periodespmi_id, $rapat->encrypted_rapat_id]) }}"
                                                icon="ti ti-edit" text="Edit" />
                                        </div>
                                        <div class="datagrid">
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Judul Kegiatan</div>
                                                <div class="datagrid-content fw-bold">{{ $rapat->judul_kegiatan }}</div>
                                            </div>
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Tanggal</div>
                                                <div class="datagrid-content">{{ formatTanggalIndo($rapat->tgl_rapat) }}</div>
                                            </div>
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Waktu</div>
                                                <div class="datagrid-content">
                                                    {{ $rapat->waktu_mulai->format('H:i') }} – {{ $rapat->waktu_selesai->format('H:i') }}
                                                    @php
                                                        $d = $rapat->waktu_mulai->diffInMinutes($rapat->waktu_selesai);
                                                        $jm = floor($d/60); $mn = $d%60;
                                                    @endphp
                                                    <span class="text-muted small">({{ $jm > 0 ? $jm.'j ' : '' }}{{ $mn > 0 ? $mn.'m' : '' }})</span>
                                                </div>
                                            </div>
                                            <div class="datagrid-item">
                                                <div class="datagrid-title">Tempat</div>
                                                <div class="datagrid-content">{{ $rapat->tempat_rapat }}</div>
                                            </div>
                                        </div>

                                        <hr class="my-3">

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h3 class="mb-0"><i class="ti ti-user-star me-2 text-orange"></i>Pejabat</h3>
                                            <x-tabler.button type="warning" class="btn-sm ajax-modal-btn"
                                                data-url="{{ route('Kegiatan.rapat.edit-officials', $rapat->hashid) }}"
                                                icon="ti ti-edit" text="Set" />
                                        </div>
                                        <div class="d-flex align-items-center mb-2 p-2 rounded bg-blue-lt">
                                            <span class="avatar avatar-sm me-3 rounded-circle bg-blue text-white">
                                                {{ strtoupper(substr($rapat->ketua_user->name ?? '?', 0, 2)) }}
                                            </span>
                                            <div>
                                                <div class="text-muted small">Ketua Rapat</div>
                                                <div class="fw-bold">{{ $rapat->ketua_user->name ?? '— Belum Diset —' }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3 p-2 rounded bg-orange-lt">
                                            <span class="avatar avatar-sm me-3 rounded-circle bg-orange text-white">
                                                {{ strtoupper(substr($rapat->notulen_user->name ?? '?', 0, 2)) }}
                                            </span>
                                            <div>
                                                <div class="text-muted small">Notulen</div>
                                                <div class="fw-bold">{{ $rapat->notulen_user->name ?? '— Belum Diset —' }}</div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <x-tabler.button href="{{ route('Kegiatan.rapat.generate-pdf', $rapat->encrypted_rapat_id) }}"
                                                class="btn-outline-danger btn-sm" icon="ti ti-file-type-pdf" text="Export PDF" />
                                        </div>
                                    </div>

                                    {{-- ── TAB: Peserta ── --}}
                                    <div class="tab-pane" id="rtm-tab-peserta">
                                        {{-- Inline Add Peserta Form --}}
                                        <div class="card card-body bg-light mb-3">
                                            <h4 class="mb-3"><i class="ti ti-user-plus me-1"></i>Tambah Peserta</h4>
                                            <form id="form-add-peserta" class="ajax-form"
                                                  action="{{ route('Kegiatan.rapat.participants.store', $rapat->hashid) }}" method="POST">
                                                @csrf
                                                <div id="peserta-rows">
                                                    <div class="row g-2 mb-2 peserta-row align-items-end">
                                                        <div class="col-md-6">
                                                            <x-tabler.form-select
                                                                name="participants[0][user_id]"
                                                                label="Pegawai"
                                                                placeholder="— Pilih Pegawai —"
                                                                required="true"
                                                                class="mb-0">
                                                                 @foreach($users as $user)
                                                                    <option value="{{ $user->encrypted_id }}">{{ $user->name }}
                                                                        @if($user->pegawai?->latestDataDiri) — {{ $user->pegawai->latestDataDiri->jabatan ?? '' }}@endif
                                                                    </option>
                                                                @endforeach
                                                            </x-tabler.form-select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <x-tabler.form-input
                                                                name="participants[0][jabatan]"
                                                                label="Jabatan / Peran"
                                                                placeholder="Contoh: Peserta"
                                                                class="mb-0"
                                                            />
                                                        </div>
                                                        <div class="col-md-2 d-flex gap-1">
                                                            <x-tabler.button type="button" class="btn-outline-danger btn-icon remove-peserta-row d-none" icon="ti ti-trash" iconOnly="true" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2 mt-2">
                                                    <x-tabler.button type="button" id="btn-add-peserta-row" class="btn-outline-secondary btn-sm" icon="ti ti-plus" text="Tambah Baris" />
                                                    <x-tabler.button type="submit" class="btn-sm ms-auto" text="Simpan Peserta" />
                                                </div>
                                            </form>
                                        </div>

                                        {{-- Daftar Peserta --}}
                                        <div class="table-responsive">
                                            <table class="table table-vcenter table-borderless mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="ps-3">Peserta</th>
                                                        <th>Jabatan</th>
                                                        <th class="text-center" style="width:100px">Kehadiran</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($rapat->pesertas as $peserta)
                                                    <tr id="row-{{ $peserta->encrypted_rapatpeserta_id }}">
                                                        <td class="ps-3">
                                                            <div class="d-flex align-items-center">
                                                                <span class="avatar avatar-sm me-2 rounded-circle
                                                                    {{ $peserta->status === 'hadir' ? 'bg-green text-white' : 'bg-secondary-lt text-muted' }}"
                                                                    id="avatar-{{ $peserta->encrypted_rapatpeserta_id }}">
                                                                    {{ strtoupper(substr($peserta->nama_display, 0, 1)) }}
                                                                </span>
                                                                <div>
                                                                    <div class="fw-medium">{{ $peserta->nama_display }}</div>
                                                                    <div class="text-muted x-small">{{ $peserta->email_display ?? '' }}</div>
                                                                    <div class="text-muted x-small waktu-hadir-{{ $peserta->encrypted_rapatpeserta_id }}
                                                                        {{ $peserta->status !== 'hadir' ? 'd-none' : '' }}">
                                                                        <i class="ti ti-clock me-1"></i>
                                                                        <span class="text-green fw-semibold">Hadir {{ $peserta->waktu_hadir?->format('H:i') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-muted small">{{ $peserta->jabatan ?? '—' }}</td>
                                                        <td class="text-center">
                                                            <label class="form-check form-switch mb-0 justify-content-center">
                                                                <input class="form-check-input attendance-switch"
                                                                    type="checkbox"
                                                                    data-url="{{ route('Kegiatan.rapat.peserta.toggle-attendance', $peserta->encrypted_rapatpeserta_id) }}"
                                                                    data-peserta-id="{{ $peserta->encrypted_rapatpeserta_id }}"
                                                                    {{ $peserta->status === 'hadir' ? 'checked' : '' }}>
                                                                <span class="form-check-label attendance-label-{{ $peserta->encrypted_rapatpeserta_id }}
                                                                    {{ $peserta->status === 'hadir' ? 'text-green fw-semibold' : 'text-muted' }}">
                                                                    {{ $peserta->status === 'hadir' ? 'Hadir' : 'Absen' }}
                                                                </span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted py-4">
                                                            <i class="ti ti-users-off fs-2 d-block mb-2"></i>
                                                            Belum ada peserta. Gunakan form di atas untuk menambahkan.
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>{{-- /tab-content --}}
                            </div>
                        </div>
                    </div>

                    {{-- ══ KANAN: Agenda (selalu tampil) ══ --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="ti ti-checklist me-2"></i>Agenda & Pembahasan</h3>
                                <div class="card-actions">
                                    <x-tabler.button type="button" class="btn-success btn-sm ajax-modal-btn"
                                        data-url="{{ route('Kegiatan.rapat.agenda.create', $rapat->encrypted_rapat_id) }}"
                                        data-modal-title="Tambah Agenda"
                                        icon="ti ti-plus" text="Tambah Agenda" />
                                    <span class="badge bg-blue-lt ms-1">{{ $rapat->agendas->count() }}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="form-agenda" action="{{ route('Kegiatan.rapat.update-agenda', $rapat->encrypted_rapat_id) }}" method="POST">
                                    @csrf
                                    <div class="accordion" id="accordion-agenda">
                                        @forelse($rapat->agendas as $index => $agenda)
                                        <div class="accordion-item bg-white">
                                            <h2 class="accordion-header" id="ah-{{ $agenda->encrypted_rapatagenda_id }}">
                                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#ac-{{ $agenda->encrypted_rapatagenda_id }}"
                                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                                    <span class="badge bg-blue-lt me-2">{{ $loop->iteration }}</span>
                                                    <span class="flex-fill">{{ $agenda->judul_agenda }}</span>
                                                    <span class="ms-2 badge save-status-{{ $agenda->encrypted_rapatagenda_id }} d-none bg-blue-lt">Saving...</span>
                                                    <div class="dropdown ms-2" onclick="event.stopPropagation()">
                                                        <a href="#" class="btn btn-ghost-secondary btn-icon btn-sm dropdown-toggle no-caret" data-bs-toggle="dropdown">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a href="javascript:void(0)" class="dropdown-item ajax-modal-btn" 
                                                                data-url="{{ route('Kegiatan.rapat.agenda.edit', $agenda->encrypted_rapatagenda_id) }}"
                                                                data-modal-title="Edit Judul Agenda">
                                                                <i class="ti ti-edit me-2"></i> Edit Judul
                                                            </a>
                                                            <a href="javascript:void(0)" class="dropdown-item text-danger ajax-confirm"
                                                                data-url="{{ route('Kegiatan.rapat.agenda.destroy', $agenda->encrypted_rapatagenda_id) }}"
                                                                data-method="DELETE"
                                                                data-title="Hapus Agenda"
                                                                data-text="Apakah Anda yakin ingin menghapus agenda ini?">
                                                                <i class="ti ti-trash me-2"></i> Hapus Agenda
                                                            </a>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="ac-{{ $agenda->encrypted_rapatagenda_id }}"
                                                class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                                data-bs-parent="#accordion-agenda">
                                                <div class="accordion-body">
                                                    <x-tabler.form-textarea
                                                        name="agendas[{{ $agenda->encrypted_rapatagenda_id }}][isi]"
                                                        data-agenda-id="{{ $agenda->encrypted_rapatagenda_id }}"
                                                        rows="6"
                                                        placeholder="Tulis notulen dan hasil pembahasan agenda ini..."
                                                        value="{{ $agenda->isi }}"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <x-tabler.empty-state
                                            title="Belum ada agenda"
                                            description="Klik Tambah Agenda untuk menambahkan agenda rapat."
                                            icon="ti ti-checklist" />
                                        @endforelse
                                    </div>

                                    @if($rapat->agendas->count() > 0)
                                    <div class="mt-3 text-end">
                                        <x-tabler.button type="submit" class="btn-primary" text="Simpan Manual" />
                                    </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                </div>{{-- /row --}}
            @endif

        </div>{{-- /#section-rtm --}}

        {{-- ===== SECTION: TAHAP 1 — DUPLIKASI STANDAR ===== --}}
        <div id="section-duplikasi" class="tab-pane fade" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-copy me-2"></i>Tahap 1 — Duplikasi Standar</h3>
                </div>
                <div class="card-body">
                    {{-- Info Banner --}}
                    <div class="alert alert-info mb-3">
                        <ul class="mb-0">
                            <li>Standar yang muncul sesuai dengan kelompok periode ini: <strong>{{ $periode->jenis_periode }}</strong></li>
                            <li>Silahkan checklist standar pada bagian <strong>"STANDAR SEBELUMNYA"</strong> lalu klik <strong>"DUPLIKASI STANDAR"</strong> untuk duplikasi ke periode selanjutnya</li>
                            <li>Klik pada judul standar untuk menampilkan isi standar</li>
                        </ul>
                    </div>

                    {{-- Kontrol: Target Periode --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                <input type="number" id="input-target-periode" class="form-control"
                                       value="{{ $periode->periode + 1 }}" placeholder="Tahun tujuan"
                                       min="2020" max="2099">
                                <x-tabler.button type="button" class="btn-outline-primary" id="btn-load-standar"
                                    icon="ti ti-refresh" text="Muat Standar" />
                            </div>
                        </div>
                        <div class="col-md-8 text-end d-flex align-items-center justify-content-end gap-2">
                            <span id="duplikasi-status" class="text-muted small"></span>
                            <x-tabler.button type="button" class="btn-primary" id="btn-duplikasi"
                                icon="ti ti-copy" text="Duplikasi Terpilih" disabled="true" />
                        </div>
                    </div>

                    {{-- Dua Panel: Standar Sebelumnya (kiri) + Standar Baru (kanan) --}}
                    <div class="row" id="panel-standar">
                        {{-- Panel Kiri: STANDAR SEBELUMNYA --}}
                        <div class="col-md-6">
                            <div class="card border-2 border-secondary">
                                <div class="card-header bg-secondary-lt">
                                    <div class="d-flex w-100 align-items-center">
                                        <h4 class="card-title mb-0"><i class="ti ti-history me-2"></i>STANDAR SEBELUMNYA</h4>
                                        <div class="ms-auto d-flex gap-2">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Aksi</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0)" class="dropdown-item" id="btn-check-all-lama"><i class="ti ti-check me-2"></i>Pilih Semua</a>
                                                    <a href="javascript:void(0)" class="dropdown-item" id="btn-uncheck-all-lama"><i class="ti ti-square me-2"></i>Bersihkan Pilihan</a>
                                                </div>
                                            </div>
                                            <input type="text" id="search-standar-lama" class="form-control form-control-sm"
                                                   placeholder="Search..." style="width: 150px">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0" id="list-standar-lama">
                                    <div class="text-center py-5 text-muted">
                                        <i class="ti ti-loader ti-spin fs-2 d-block mb-2"></i>
                                        Memuat data standar...
                                    </div>
                                </div>
                                <div class="card-footer bg-light" id="footer-standar-lama">
                                    <small class="text-muted">Standar ke <span id="count-lama-from">0</span> hingga <span id="count-lama-to">0</span> dari <span id="count-lama-total">0</span> Standar</small>
                                </div>
                            </div>
                        </div>

                        {{-- Panel Kanan: STANDAR BARU --}}
                        <div class="col-md-6">
                            <div class="card border-2 border-success">
                                <div class="card-header bg-green-lt">
                                    <div class="d-flex w-100 align-items-center">
                                        <h4 class="card-title mb-0"><i class="ti ti-sparkles me-2"></i>STANDAR BARU</h4>
                                        <span class="badge bg-green ms-2" id="badge-new-periode">{{ $periode->periode + 1 }}</span>
                                        <div class="ms-auto d-flex gap-2">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">Aksi</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0)" class="dropdown-item" id="btn-check-all-baru"><i class="ti ti-check me-2"></i>Pilih Semua</a>
                                                    <a href="javascript:void(0)" class="dropdown-item" id="btn-uncheck-all-baru"><i class="ti ti-square me-2"></i>Bersihkan Pilihan</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="javascript:void(0)" class="dropdown-item text-danger disabled" id="btn-hapus-bulk"><i class="ti ti-trash me-2 text-red"></i>Hapus Terpilih <span id="hapus-count-badge" class="badge bg-red ms-auto d-none">0</span></a>
                                                </div>
                                            </div>
                                            <input type="text" id="search-standar-baru" class="form-control form-control-sm"
                                                   placeholder="Search..." style="width: 150px">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0" id="list-standar-baru">
                                    <div class="text-center py-5 text-muted">
                                        <i class="ti ti-loader ti-spin fs-2 d-block mb-2"></i>
                                        Memuat data standar...
                                    </div>
                                </div>
                                <div class="card-footer bg-light" id="footer-standar-baru">
                                    <small class="text-muted">Standar ke <span id="count-baru-from">0</span> hingga <span id="count-baru-to">0</span> dari <span id="count-baru-total">0</span> Standar</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== SECTION: TAHAP 2 — REVIEW INDIKATOR ===== --}}
        <div id="section-manage" class="tab-pane fade" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-settings-2 me-2"></i>Tahap 2 — Review Indikator</h3>
                </div>
                <div class="card-body">
                    @if(!$hasDuplicated)
                        <div class="text-center py-4">
                            <span class="avatar avatar-xl rounded bg-yellow-lt mb-3">
                                <i class="ti ti-alert-triangle fs-1"></i>
                            </span>
                            <h3>Duplikasi Belum Dilakukan</h3>
                            <p class="text-muted">Lakukan duplikasi standar terlebih dahulu di <strong>Tahap 1</strong> sebelum melakukan review.</p>
                            <x-tabler.button type="button" class="btn-outline-primary" onclick="switchSection('#section-duplikasi')" icon="ti ti-arrow-left" text="Ke Tahap 1" />
                        </div>
                    @else
                        <div class="alert alert-info mb-3">
                            <i class="ti ti-info-circle me-1"></i>
                            Tabel di bawah menampilkan indikator yang sudah diduplikasi beserta <strong>status pengendalian tahun lalu</strong> untuk setiap prodi.
                        </div>
                        <div class="table-responsive">
                            <x-tabler.datatable
                                id="table-review"
                                route="{{ route('pemutu.peningkatan.review-data', $periode->encrypted_periodespmi_id) }}"
                                :columns="[
                                    ['data' => 'no_indikator', 'name' => 'pemutu_indikator.no_indikator', 'title' => 'No.', 'width' => '5%'],
                                    ['data' => 'nama_indikator', 'name' => 'pemutu_indikator.indikator', 'title' => 'Nama Indikator'],
                                    ['data' => 'dokumen_standar', 'name' => 'd.judul', 'title' => 'Standar / Dokumen'],
                                    ['data' => 'nama_prodi', 'name' => 'org.nama', 'title' => 'Prodi/Unit', 'width' => '10%'],
                                    ['data' => 'target_baru', 'name' => 'pemutu_indikator_orgunit.target', 'title' => 'Target Baru', 'width' => '5%', 'class' => 'text-center'],
                                    ['data' => 'status_badge', 'name' => 'status_badge', 'title' => 'Status Thn Lalu', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                    ['data' => 'keterangan_perubahan', 'name' => 'keterangan_perubahan', 'title' => 'Keterangan Perubahan', 'orderable' => false, 'searchable' => false]
                                ]"
                            />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        </div>{{-- /tab-content --}}
    </div>{{-- /col-12 --}}
</div>{{-- /row --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Native Bootstrap Tab Persistence ───────────────────────
    const urlHash = window.location.hash;
    if (urlHash && urlHash.startsWith('#section-')) {
        const triggerEl = document.querySelector(`.nav-pills a[href="${urlHash}"]`);
        if (triggerEl) {
            const tab = new bootstrap.Tab(triggerEl);
            tab.show();
        }
    }

    // Update hash on tab change
    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function(el) {
        el.addEventListener('shown.bs.tab', function (e) {
            if(history.pushState) {
                history.pushState(null, null, e.target.hash);
            }
            else {
                window.location.hash = e.target.hash;
            }
        });
    });

    window.switchSection = function(targetId) {
        const triggerEl = document.querySelector(`.nav-pills a[href="${targetId}"]`);
        if (triggerEl) {
            const tab = new bootstrap.Tab(triggerEl);
            tab.show();
        }
    };

    // ─── Attendance Switch Toggle ─────────────────────────────
    document.querySelectorAll('.attendance-switch').forEach(sw => {
        sw.addEventListener('change', function () {
            const pesertaId = this.dataset.pesertaId;
            const url = this.dataset.url;
            const $label = document.querySelector('.attendance-label-' + pesertaId);
            const $waktu = document.querySelector('.waktu-hadir-' + pesertaId);
            const $avatar = document.querySelector('#avatar-' + pesertaId);
            const isChecked = this.checked;

            // Optimistic UI
            $label.textContent = isChecked ? 'Hadir' : 'Absen';
            $label.className = `form-check-label attendance-label-${pesertaId} ${isChecked ? 'text-green fw-semibold' : 'text-muted'}`;
            if ($waktu) $waktu.classList.toggle('d-none', !isChecked);
            if ($avatar) {
                $avatar.className = `avatar avatar-sm me-2 rounded-circle ${isChecked ? 'bg-green text-white' : 'bg-secondary-lt text-muted'}`;
            }

            axios.patch(url, { _token: '{{ csrf_token() }}' })
                .then(res => {
                    if (res.data.success && res.data.waktu_hadir && $waktu) {
                        const span = $waktu.querySelector('.text-green');
                        if (span) span.textContent = `Hadir ${res.data.waktu_hadir}`;
                    }
                })
                .catch(() => {
                    this.checked = !isChecked;
                    showErrorMessage('Gagal', 'Gagal memperbarui absensi.');
                });
        });
    });

    // ─── HugeRTE Auto-save for Agenda Notulen ─────────────────
    @if($rapat)
    if (window.loadHugeRTE) {
        window.loadHugeRTE('textarea[data-agenda-id]', {
            height: 280,
            menubar: false,
            statusbar: false,
            plugins: 'lists link table',
            toolbar: 'bold italic underline | bullist numlist | link | table | undo redo',
            setup: function (editor) {
                let timeout;
                editor.on('input change keyup', function () {
                    clearTimeout(timeout);
                    const agendaId = editor.getElement().dataset.agendaId;
                    const $s = document.querySelector('.save-status-' + agendaId);
                    if ($s) { $s.classList.remove('d-none', 'bg-green-lt', 'bg-red-lt'); $s.classList.add('bg-blue-lt'); $s.textContent = 'Typing...'; }
                    timeout = setTimeout(() => autoSaveAgenda(agendaId, editor.getContent()), 1500);
                });
            }
        });
    }

    function autoSaveAgenda(agendaId, content) {
        const $s = document.querySelector('.save-status-' + agendaId);
        if ($s) { $s.classList.remove('d-none', 'bg-green-lt', 'bg-red-lt'); $s.classList.add('bg-blue-lt'); $s.textContent = 'Saving...'; }
        const payload = { _token: '{{ csrf_token() }}', agendas: { [agendaId]: { isi: content } } };
        axios.post('{{ route('Kegiatan.rapat.update-agenda', $rapat->encrypted_rapat_id) }}', payload)
            .then(() => {
                if ($s) { $s.textContent = 'Saved ✓'; $s.classList.replace('bg-blue-lt', 'bg-green-lt'); setTimeout(() => $s.classList.add('d-none'), 2000); }
            })
            .catch(() => {
                if ($s) { $s.textContent = 'Error ✗'; $s.classList.replace('bg-blue-lt', 'bg-red-lt'); }
            });
    }
    @endif

    // ─── Inline Peserta Row Management ────────────────────────
    let pesertaRowCount = 1;
    const pesertaContainer = document.getElementById('peserta-rows');
    const addRowBtn = document.getElementById('btn-add-peserta-row');

    if (addRowBtn && pesertaContainer) {
        addRowBtn.addEventListener('click', function () {
            const idx = pesertaRowCount++;
            const firstRow = pesertaContainer.querySelector('.peserta-row');
            const newRow = firstRow.cloneNode(true);

            // Update names
            newRow.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/participants\[\d+\]/, `participants[${idx}]`);
                if (el.tagName === 'SELECT') el.selectedIndex = 0;
                if (el.tagName === 'INPUT') el.value = '';
            });

            // Show remove button
            const removeBtn = newRow.querySelector('.remove-peserta-row');
            if (removeBtn) removeBtn.classList.remove('d-none');

            pesertaContainer.appendChild(newRow);
            updateRemoveButtons();
        });

        pesertaContainer.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.remove-peserta-row');
            if (removeBtn) {
                removeBtn.closest('.peserta-row').remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            const rows = pesertaContainer.querySelectorAll('.peserta-row');
            rows.forEach((row, i) => {
                const btn = row.querySelector('.remove-peserta-row');
                if (btn) btn.classList.toggle('d-none', rows.length <= 1);
            });
        }
    }

    // ─── Reload on AJAX form success ──────────────────────────
    document.addEventListener('ajax-form:success', function () {
        setTimeout(() => location.reload(), 500);
    });

    // ─── TAHAP 1: Dua Panel Duplikasi ─────────────────────────
    const btnLoadStandar = document.getElementById('btn-load-standar');
    const inputTargetPeriode = document.getElementById('input-target-periode');
    const badgeNewPeriode = document.getElementById('badge-new-periode');
    const btnDuplikasi = document.getElementById('btn-duplikasi');
    const btnHapusBulk = document.getElementById('btn-hapus-bulk');
    const hapusCountBadge = document.getElementById('hapus-count-badge');
    const panelStandar = document.getElementById('panel-standar');
    const statusText = document.getElementById('duplikasi-status');

    let allStandarLama = [];
    let allStandarBaru = [];
    let selectedDokIdsLama = new Set();
    let selectedDokIdsBaru = new Set();

    if (btnLoadStandar && panelStandar) {
        // Render helper
        const renderList = (data, containerId, isLama, searchTerm = '') => {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            let filtered = data;
            if (searchTerm) {
                const s = searchTerm.toLowerCase();
                filtered = data.filter(d => d.judul.toLowerCase().includes(s) || (d.kode && d.kode.toLowerCase().includes(s)));
            }

            if (filtered.length === 0) {
                container.innerHTML = '<div class="text-center py-4 text-muted fst-italic">Tidak ada standar ditemukan</div>';
            } else {
                const list = document.createElement('div');
                list.className = 'list-group list-group-flush';

                filtered.forEach(d => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item px-3 py-2 d-flex align-items-center';

                    // Kiri: Checkbox
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.className = 'form-check-input me-3 check-standar';
                    checkbox.value = d.dok_id;
                    
                    if (isLama) {
                        checkbox.checked = selectedDokIdsLama.has(d.dok_id);

                        // Disable jika sudah diduplikasi
                        if (d.already_duplicated) {
                            checkbox.disabled = true;
                            item.classList.add('bg-light', 'text-muted');
                            checkbox.title = 'Sudah diduplikasi';
                        } else {
                            checkbox.addEventListener('change', (e) => {
                                if (e.target.checked) selectedDokIdsLama.add(d.dok_id);
                                else selectedDokIdsLama.delete(d.dok_id);
                                updateBtnStates();
                            });
                        }
                    } else {
                        // Untuk Panel Standar Baru (Hapus)
                        checkbox.checked = selectedDokIdsBaru.has(d.dok_id);
                        checkbox.addEventListener('change', (e) => {
                            if (e.target.checked) selectedDokIdsBaru.add(d.dok_id);
                            else selectedDokIdsBaru.delete(d.dok_id);
                            updateBtnStates();
                        });
                    }
                    item.appendChild(checkbox);

                    // Judul + Kode
                    const textDiv = document.createElement('div');
                    textDiv.className = 'flex-fill';
                    let textHtml = `<div class="fw-medium">${d.judul}</div>`;
                    if (d.kode) textHtml += `<div class="text-muted small">${d.kode}</div>`;
                    textDiv.innerHTML = textHtml;
                    item.appendChild(textDiv);

                    // Kanan: Tags/Info
                    const infoDiv = document.createElement('div');
                    infoDiv.className = 'ms-auto text-end';

                    if (isLama) {
                        if (d.indikator_count > 0) {
                            infoDiv.innerHTML += `<span class="badge bg-blue-lt ms-2" title="${d.indikator_count} Indikator target">${d.indikator_count} Ind.</span>`;
                        }
                        if (d.already_duplicated) {
                             infoDiv.innerHTML += `<span class="badge bg-green-lt ms-2"><i class="ti ti-check me-1"></i>Ada di Target</span>`;
                        }
                    } else {
                        // Di panel baru
                        if (d.indikator_count > 0) {
                           infoDiv.innerHTML += `<span class="badge bg-green-lt ms-2" title="${d.indikator_count} Indikator">${d.indikator_count} Ind.</span>`;
                        } else {
                           infoDiv.innerHTML += `<span class="badge bg-secondary-lt ms-2" title="Belum ada indikator kelompok ini diduplikasi ke dokumen ini">0 Ind.</span>`;
                        }
                    }

                    item.appendChild(infoDiv);
                    list.appendChild(item);
                });
                container.appendChild(list);
            }

            // Update footer counts
            const total = data.length;
            const shows = filtered.length;
            const prefix = isLama ? 'lama' : 'baru';
            document.getElementById(`count-${prefix}-from`).textContent = shows > 0 ? 1 : 0;
            document.getElementById(`count-${prefix}-to`).textContent = shows;
            document.getElementById(`count-${prefix}-total`).textContent = total;
        };

        const updateBtnStates = () => {
            const countLama = selectedDokIdsLama.size;
            btnDuplikasi.disabled = countLama === 0;
            btnDuplikasi.innerText = countLama > 0 ? `Duplikasi ${countLama} Standar` : 'Duplikasi Terpilih';
            statusText.innerText = countLama > 0 ? `${countLama} dichecklist` : '';

            const countBaru = selectedDokIdsBaru.size;
            if (countBaru > 0) {
                btnHapusBulk.classList.remove('disabled');
                hapusCountBadge.classList.remove('d-none');
                hapusCountBadge.textContent = countBaru;
            } else {
                btnHapusBulk.classList.add('disabled');
                hapusCountBadge.classList.add('d-none');
                hapusCountBadge.textContent = '0';
            }
        };

        const loadStandar = () => {
            const tPeriode = inputTargetPeriode.value;
            if (!tPeriode || tPeriode < 2020) return;

            badgeNewPeriode.innerText = tPeriode;
            document.getElementById('list-standar-lama').innerHTML = '<div class="text-center py-5 text-muted"><i class="ti ti-loader ti-spin fs-2 d-block mb-2"></i>Memuat...</div>';
            document.getElementById('list-standar-baru').innerHTML = '<div class="text-center py-5 text-muted"><i class="ti ti-loader ti-spin fs-2 d-block mb-2"></i>Memuat...</div>';

            axios.get('{{ route('pemutu.peningkatan.standar-list', $periode->encrypted_periodespmi_id) }}', {
                params: { target_periode: tPeriode }
            })
            .then(res => {
                if (res.data.success) {
                    allStandarLama = res.data.data.standar_lama;
                    allStandarBaru = res.data.data.standar_baru;

                    // Auto-select yang belum diduplikasi
                    selectedDokIdsLama.clear();
                    allStandarLama.forEach(d => {
                        if (!d.already_duplicated) selectedDokIdsLama.add(d.dok_id);
                    });
                    
                    selectedDokIdsBaru.clear(); // Reset hapus bulk

                    renderList(allStandarLama, 'list-standar-lama', true);
                    renderList(allStandarBaru, 'list-standar-baru', false);
                    updateBtnStates();
                } else {
                    toastError('Gagal memuat daftar standar');
                }
            })
            .catch(err => toastError('Terjadi kesalahan server saat memuat standar'));
        };

        // Event listeners
        btnLoadStandar.addEventListener('click', loadStandar);
        inputTargetPeriode.addEventListener('change', loadStandar);

        document.getElementById('search-standar-lama').addEventListener('input', (e) => {
            renderList(allStandarLama, 'list-standar-lama', true, e.target.value);
        });
        document.getElementById('search-standar-baru').addEventListener('input', (e) => {
            renderList(allStandarBaru, 'list-standar-baru', false, e.target.value);
        });

        // --- Action Ceklis LAMA ---
        document.getElementById('btn-check-all-lama').addEventListener('click', () => {
            allStandarLama.forEach(d => {
                if (!d.already_duplicated) selectedDokIdsLama.add(d.dok_id);
            });
            renderList(allStandarLama, 'list-standar-lama', true, document.getElementById('search-standar-lama').value);
            updateBtnStates();
        });
        document.getElementById('btn-uncheck-all-lama').addEventListener('click', () => {
            selectedDokIdsLama.clear();
            renderList(allStandarLama, 'list-standar-lama', true, document.getElementById('search-standar-lama').value);
            updateBtnStates();
        });

        // --- Action Ceklis BARU ---
        document.getElementById('btn-check-all-baru').addEventListener('click', () => {
            allStandarBaru.forEach(d => selectedDokIdsBaru.add(d.dok_id));
            renderList(allStandarBaru, 'list-standar-baru', false, document.getElementById('search-standar-baru').value);
            updateBtnStates();
        });
        document.getElementById('btn-uncheck-all-baru').addEventListener('click', () => {
            selectedDokIdsBaru.clear();
            renderList(allStandarBaru, 'list-standar-baru', false, document.getElementById('search-standar-baru').value);
            updateBtnStates();
        });

        btnDuplikasi.addEventListener('click', () => {
            if (selectedDokIdsLama.size === 0) return;

            Swal.fire({
                title: 'Duplikasi Standar',
                text: `Anda yakin ingin menduplikasi ${selectedDokIdsLama.size} standar beserta indikatornya ke periode ${inputTargetPeriode.value}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#206bc4',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Duplikasi!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(),
                preConfirm: () => {
                    return axios.post('{{ route('pemutu.peningkatan.duplikasi', $periode->encrypted_periodespmi_id) }}', {
                        target_periode: inputTargetPeriode.value,
                        selected_dok_ids: Array.from(selectedDokIdsLama)
                    })
                    .then(res => {
                        if (!res.data.success) {
                            throw new Error(res.data.message || 'Terjadi kesalahan saat duplikasi.');
                        }
                        return res.data;
                    })
                    .catch(err => {
                        let errorMessage = 'Gagal terhubung ke server.';
                        if (err.response && err.response.data && err.response.data.message) {
                            errorMessage = err.response.data.message;
                        } else if (err.message) {
                            errorMessage = err.message;
                        }
                        Swal.showValidationMessage(`Gagal: ${errorMessage}`);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: result.value.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        loadStandar(); // Reload lists without refreshing page
                    });
                }
            });
        });

        // Bulk Delete Button Logic
        btnHapusBulk.addEventListener('click', () => {
            if (selectedDokIdsBaru.size === 0) return;

            const tPeriode = inputTargetPeriode.value;

            Swal.fire({
                title: 'Hapus Standar Terpilih?',
                html: `Anda yakin ingin menghapus <b>${selectedDokIdsBaru.size} Standar</b> dari periode <b>${tPeriode}</b>?<br><br><small class="text-danger">Aksi ini akan menghapus dokumen rujukan beserta semua indikator hasil duplikasinya di periode ini. Hal ini tidak dapat dibatalkan!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus Terpilih!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(),
                preConfirm: () => {
                    return axios.delete('{{ route('pemutu.peningkatan.delete-standar-bulk', $periode->encrypted_periodespmi_id) }}', {
                        data: {
                            target_periode: tPeriode,
                            selected_dok_ids: Array.from(selectedDokIdsBaru)
                        }
                    })
                    .then(res => {
                        if (!res.data.success) {
                            throw new Error(res.data.message || 'Terjadi kesalahan saat menghapus.');
                        }
                        return res.data;
                    })
                    .catch(err => {
                        let errorMessage = 'Gagal terhubung ke server.';
                        if (err.response && err.response.data && err.response.data.message) {
                            errorMessage = err.response.data.message;
                        } else if (err.message) {
                            errorMessage = err.message;
                        }
                        Swal.showValidationMessage(`Gagal: ${errorMessage}`);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: result.value.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        loadStandar();
                    });
                }
            });
        });

        // Load awal
        setTimeout(loadStandar, 500);
    }
});
</script>
@endpush
