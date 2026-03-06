@extends('layouts.tabler.app')
@section('title', 'RTM Pengendalian — Periode ' . $periode->periode)

@section('header')
@php
    $pretitle = 'Periode ' . $periode->periode . ' · ' . $periode->jenis_periode;
    if ($periode->pengendalian_awal && $periode->pengendalian_akhir) {
        $pretitle .= ' · ' . formatTanggalIndo($periode->pengendalian_awal) . ' s/d ' . formatTanggalIndo($periode->pengendalian_akhir);
    }
@endphp
<x-tabler.page-header title="RTM Pengendalian" :pretitle="$pretitle">
    <x-slot:actions>
        <x-tabler.button type="back" href="{{ route('pemutu.pengendalian.index') }}" size="sm" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row">

    {{-- Tab Navigation --}}
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body p-2">
                <ul class="nav nav-pills" id="pengendalian-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#section-rtm" class="nav-link active" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-calendar-event me-2"></i> Data Umum & Agenda
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#section-pengendalian" class="nav-link" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-settings-check me-2"></i> Pengendalian Standar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="tab-content" id="pengendalian-tab-content">

        {{-- ===== SECTION: RTM (Data Umum & Agenda) ===== --}}
        <div id="section-rtm" class="tab-pane fade show active" role="tabpanel">

            @if(!$rapat)
                {{-- RTM Belum Ada --}}
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <span class="avatar avatar-xl rounded bg-teal-lt">
                                <i class="ti ti-calendar-plus fs-1"></i>
                            </span>
                        </div>
                        <h3>Belum Ada RTM</h3>
                        <p class="text-muted">Buat Rapat Tinjauan Manajemen untuk memulai proses pengendalian periode ini.</p>
                        <x-tabler.button type="create" class="ajax-modal-btn"
                            data-url="{{ route('pemutu.pengendalian.rtm.create', $periode->encrypted_periodespmi_id) }}"
                            data-modal-title="Buat RTM Pengendalian"
                            text="Buat RTM" />
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
                                            <h3 class="mb-0"><i class="ti ti-calendar-event me-2 text-teal"></i>Info Rapat</h3>
                                            <x-tabler.button type="button" class="btn-outline-primary btn-sm ajax-modal-btn"
                                                data-url="{{ route('pemutu.pengendalian.rtm.edit', [$periode->encrypted_periodespmi_id, $rapat->encrypted_rapat_id]) }}"
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
                                                    <x-tabler.button type="create" id="btn-add-peserta-row" class="btn-outline-secondary btn-sm" text="Tambah Baris" />
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
                                    <x-tabler.button type="create" class="btn-success btn-sm ajax-modal-btn"
                                        data-url="{{ route('Kegiatan.rapat.agenda.create', $rapat->encrypted_rapat_id) }}"
                                        data-modal-title="Tambah Agenda"
                                        text="Tambah Agenda" />
                                    <span class="badge bg-teal-lt ms-1">{{ $rapat->agendas->count() }}</span>
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
                                                    <span class="badge bg-teal-lt me-2">{{ $loop->iteration }}</span>
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

        {{-- ===== SECTION: PENGENDALIAN STANDAR ===== --}}
        <div id="section-pengendalian" class="tab-pane fade" role="tabpanel">
            <div class="card">
                <div class="card-header border-bottom py-3">
                    <h3 class="card-title">Daftar Indikator</h3>
                </div>
                <div class="card-body p-0">
                    <x-tabler.datatable
                        id="table-pengendalian"
                        route="{{ route('pemutu.pengendalian.data', $periode->encrypted_periodespmi_id) }}"
                        :columns="[
                            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '4%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                            ['data' => 'indikator_info', 'name' => 'indikator', 'title' => 'Indikator'],
                            ['data' => 'analisis', 'name' => 'analisis', 'title' => 'Analisis', 'orderable' => false, 'searchable' => false],
                            ['data' => 'status_ami', 'name' => 'status_ami', 'title' => 'AMI', 'width' => '8%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                            ['data' => 'status_pengend', 'name' => 'status_pengend', 'title' => 'Status', 'width' => '9%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                            ['data' => 'eisenhower_matrix', 'name' => 'eisenhower_matrix', 'title' => 'Matrix', 'width' => '9%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '7%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ]"
                    />
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

    // ─── Eisenhower Matrix inline AJAX ────────────────────────
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('matrix-radio')) return;

        const radio      = e.target;
        const indorgunit = radio.dataset.indorgunit;
        const field      = radio.dataset.field;
        const value      = radio.value;

        axios.post('{{ url("pemutu/pengendalian/matrix") }}/' + indorgunit, {
            [field]: value,
            _token: document.querySelector('meta[name="csrf-token"]')?.content
        }).catch(err => {
            console.error('Gagal update matrix:', err);
        });
    });

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
});
</script>
@endpush
