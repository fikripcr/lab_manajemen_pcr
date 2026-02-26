@extends('layouts.tabler.app')
@section('title', $pageTitle)

@section('header')
<x-tabler.page-header title="{{ $rapat->judul_kegiatan }}" pretitle="{{ $rapat->jenis_rapat }}">
    <x-slot:actions>
        <x-tabler.button href="{{ route('Kegiatan.rapat.generate-pdf', $rapat->encrypted_rapat_id) }}"
            class="btn-outline-danger btn-sm" icon="ti ti-file-type-pdf" text="Export PDF" />
        <x-tabler.button type="back" href="{{ route('Kegiatan.rapat.index') }}" />
    </x-slot:actions>
    <div class="text-muted mt-1">
        <i class="ti ti-calendar me-1"></i> {{ formatTanggalIndo($rapat->tgl_rapat) }}
        &bull; <i class="ti ti-clock me-1"></i> {{ $rapat->waktu_mulai->format('H:i') }} - {{ $rapat->waktu_selesai->format('H:i') }}
        &bull; <i class="ti ti-map-pin me-1"></i> {{ $rapat->tempat_rapat }}
    </div>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row">

    {{-- Segmented Control Navigation --}}
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body p-2">
                <div class="nav nav-pills">
                    <a href="#section-info" class="nav-link active" data-section="info">
                        <i class="ti ti-info-circle me-2"></i> Info & Peserta
                        <span class="badge bg-blue-lt ms-1">{{ $rapat->pesertas->count() }}</span>
                    </a>
                    <a href="#section-agenda" class="nav-link" data-section="agenda">
                        <i class="ti ti-checklist me-2"></i> Agenda & Notulen
                        <span class="badge bg-blue-lt ms-1">{{ $rapat->agendas->count() }}</span>
                    </a>
                    <a href="#section-entitas" class="nav-link" data-section="entitas">
                        <i class="ti ti-link me-2"></i> Entitas Terkait
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">

        {{-- ===== SECTION: INFO & PESERTA ===== --}}
        <div id="section-info" class="content-section">
            <div class="row row-cards">

                {{-- Kolom Kiri: Info Rapat + Pejabat --}}
                <div class="col-md-5">
                    {{-- Info Rapat --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><i class="ti ti-calendar-event me-2 text-blue"></i>Informasi Meeting</h3>
                            <div class="card-actions">
                                <x-tabler.button href="{{ route('Kegiatan.rapat.edit', $rapat->encrypted_rapat_id) }}"
                                    class="btn-primary btn-sm" icon="ti ti-edit" text="Edit" />
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Jenis Rapat</div>
                                    <div class="datagrid-content"><span class="badge bg-blue-lt">{{ $rapat->jenis_rapat }}</span></div>
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
                                @if($rapat->keterangan)
                                <div class="datagrid-item" style="grid-column: 1/-1">
                                    <div class="datagrid-title">Keterangan</div>
                                    <div class="datagrid-content text-muted">{{ $rapat->keterangan }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Pejabat Rapat --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="ti ti-user-star me-2 text-orange"></i>Pejabat Rapat</h3>
                            <div class="card-actions">
                                <x-tabler.button type="warning" class="btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#modal-set-officials"
                                    icon="ti ti-edit" text="Set" />
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3 p-2 rounded bg-blue-lt">
                                <span class="avatar avatar-sm me-3 rounded-circle bg-blue text-white">
                                    {{ strtoupper(substr($rapat->ketua_user->name ?? '?', 0, 2)) }}
                                </span>
                                <div>
                                    <div class="text-muted small">Ketua Rapat</div>
                                    <div class="fw-bold">{{ $rapat->ketua_user->name ?? '— Belum Diset —' }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center p-2 rounded bg-orange-lt">
                                <span class="avatar avatar-sm me-3 rounded-circle bg-orange text-white">
                                    {{ strtoupper(substr($rapat->notulen_user->name ?? '?', 0, 2)) }}
                                </span>
                                <div>
                                    <div class="text-muted small">Notulen</div>
                                    <div class="fw-bold">{{ $rapat->notulen_user->name ?? '— Belum Diset —' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Daftar Peserta + Absensi Switch --}}
                <div class="col-md-7">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title"><i class="ti ti-users me-2 text-green"></i>Daftar Peserta & Absensi</h3>
                            <div class="card-actions">
                                <x-tabler.button type="primary" class="btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#modal-add-participants"
                                    icon="ti ti-user-plus" text="Tambah Peserta" />
                                <span class="badge bg-green-lt ms-1">{{ $rapat->pesertas->count() }} orang</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-vcenter table-borderless mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-3">Peserta</th>
                                            <th>Email</th>
                                            <th>Jabatan</th>
                                            <th class="text-center" style="width:130px">Kehadiran</th>
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
                                                        <div class="fw-medium">{{ $peserta->nama_display }}
                                                            @if(!$peserta->user_id)
                                                                <span class="badge bg-yellow-lt ms-1" title="Peserta Luar">Luar</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-muted x-small waktu-hadir-{{ $peserta->encrypted_rapatpeserta_id }}
                                                            {{ $peserta->status !== 'hadir' ? 'd-none' : '' }}">
                                                            <i class="ti ti-clock me-1"></i>
                                                            <span class="text-green fw-semibold">Hadir {{ $peserta->waktu_hadir?->format('H:i') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted small">{{ $peserta->email_display ?? '—' }}</td>
                                            <td class="text-muted small">{{ $peserta->jabatan }}</td>
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
                                            <td colspan="4" class="text-center text-muted py-5">
                                                <i class="ti ti-users-off fs-1 d-block mb-2"></i>
                                                Belum ada peserta. Klik Tambah Peserta.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /row --}}
        </div>{{-- /#section-info --}}

        {{-- ===== SECTION: AGENDA & NOTULEN ===== --}}
        <div id="section-agenda" class="content-section">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-0"><i class="ti ti-checklist me-2"></i>Agenda & Pembahasan</h3>
                </div>
                <x-tabler.button type="button" class="btn-success btn-sm ajax-modal-btn"
                    data-url="{{ route('Kegiatan.rapat.agenda.create', $rapat->encrypted_rapat_id) }}"
                    data-modal-title="Tambah Agenda"
                    icon="ti ti-plus" text="Tambah Agenda" />
            </div>

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
                                <span class="badge bg-purple-lt me-2">{{ $loop->iteration }}</span>
                                {{ $agenda->judul_agenda }}
                                <span class="ms-2 badge save-status-{{ $agenda->encrypted_rapatagenda_id }} d-none bg-blue-lt">Saving...</span>
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
                    <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy" text="Simpan Manual" />
                </div>
                @endif
            </form>
        </div>

        {{-- ===== SECTION: ENTITAS TERKAIT ===== --}}
        <div id="section-entitas" class="content-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-link me-2 text-cyan"></i>Entitas Terkait</h3>
                    <div class="card-actions">
                        <x-tabler.button type="button" class="btn-sm ajax-modal-btn"
                            data-url="{{ route('Kegiatan.rapat.entitas.create', $rapat->encrypted_rapat_id) }}"
                            data-modal-title="Tambah Entitas Terkait"
                            icon="ti ti-plus" text="Tambah Entitas" />
                    </div>
                </div>
                <div class="card-body">
                    @forelse($rapat->entitas as $entitas)
                    <div class="d-flex align-items-start mb-3 p-2 border rounded">
                        <span class="avatar bg-cyan-lt me-3"><i class="ti ti-database"></i></span>
                        <div>
                            <div class="fw-bold small">{{ $entitas->model }}</div>
                            <div class="text-muted small">ID: {{ $entitas->model_id }}</div>
                            @if($entitas->keterangan)
                                <div class="text-muted x-small">{{ Str::limit($entitas->keterangan, 80) }}</div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <x-tabler.empty-state title="Tidak ada entitas terkait"
                        description="Hubungkan rapat ini dengan entitas lain."
                        icon="ti ti-link" />
                    @endforelse
                </div>
            </div>
        </div>

    </div>{{-- /col-12 --}}
</div>{{-- /row --}}

{{-- MODAL: Set Pejabat Rapat --}}
<x-tabler.form-modal id="modal-set-officials" title="Set Pejabat Rapat"
    :route="route('Kegiatan.rapat.update-officials', $rapat->encrypted_rapat_id)">
    <x-tabler.form-select name="ketua_user_id" label="Ketua Rapat" type="select2" required="true" class="select2-officials">
        <option value="" selected disabled>Pilih Ketua Rapat</option>
        @foreach($rapat->pesertas as $peserta)
            <option value="{{ $peserta->user_id }}" {{ $rapat->ketua_user_id == $peserta->user_id ? 'selected' : '' }}>
                {{ $peserta->user->name }}
            </option>
        @endforeach
    </x-tabler.form-select>
    <x-tabler.form-select name="notulen_user_id" label="Notulen" type="select2" required="true" class="select2-officials">
        <option value="" selected disabled>Pilih Notulen</option>
        @foreach($rapat->pesertas as $peserta)
            <option value="{{ $peserta->user_id }}" {{ $rapat->notulen_user_id == $peserta->user_id ? 'selected' : '' }}>
                {{ $peserta->user->name }}
            </option>
        @endforeach
    </x-tabler.form-select>
</x-tabler.form-modal>

{{-- MODAL: Tambah Peserta --}}
<x-tabler.form-modal id="modal-add-participants" title="Tambah Peserta Rapat"
    :route="route('Kegiatan.rapat.participants.store', $rapat->encrypted_rapat_id)">

    <ul class="nav nav-tabs mb-3" id="tab-tambah-peserta" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#tab-internal">
                <i class="ti ti-users me-1"></i> Pegawai Internal
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-luar">
                <i class="ti ti-user-plus me-1"></i> Peserta Luar
            </a>
        </li>
    </ul>

    <div class="tab-content">
        {{-- Tab Internal --}}
        <div class="tab-pane active show" id="tab-internal">
            <div class="mb-3">
                <label class="form-label">Pilih Peserta <span class="text-muted small">(bisa pilih banyak)</span></label>
                <select name="user_ids[]" class="form-select select2-participants" multiple="multiple"
                    data-placeholder="Cari pegawai...">
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} — {{ $user->email }}</option>
                    @endforeach
                </select>
            </div>
            <x-tabler.form-input name="jabatan_internal" label="Jabatan / Peran" placeholder="Contoh: Peserta, Narasumber" />
        </div>

        {{-- Tab Luar --}}
        <div class="tab-pane" id="tab-luar">
            <div id="peserta-luar-list">
                <div class="row g-2 mb-2 peserta-luar-row">
                    <div class="col">
                        <input type="text" name="peserta_luar[0][nama]" class="form-control" placeholder="Nama lengkap" required>
                    </div>
                    <div class="col">
                        <input type="email" name="peserta_luar[0][email]" class="form-control" placeholder="Email">
                    </div>
                    <div class="col">
                        <input type="text" name="peserta_luar[0][jabatan]" class="form-control" placeholder="Jabatan/Peran">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="btn-add-luar">
                <i class="ti ti-plus me-1"></i> Tambah Baris
            </button>
        </div>
    </div>
</x-tabler.form-modal>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Segmented Control ──────────────────────────────────────
    function switchSection(targetId) {
        document.querySelectorAll('.content-section').forEach(s => s.style.display = 'none');
        const t = document.querySelector(targetId);
        if (t) t.style.display = 'block';
        document.querySelectorAll('.nav-pills .nav-link').forEach(l =>
            l.classList.toggle('active', l.getAttribute('href') === targetId));
    }
    document.querySelectorAll('.nav-pills .nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            if (this.getAttribute('href').startsWith('#section-')) {
                e.preventDefault();
                switchSection(this.getAttribute('href'));
                history.pushState(null, null, this.getAttribute('href'));
            }
        });
    });
    const hash = window.location.hash;
    switchSection(hash && hash.startsWith('#section-') && document.querySelector(hash) ? hash : '#section-info');

    // ─── Attendance Switch Toggle ─────────────────────────────
    document.querySelectorAll('.attendance-switch').forEach(sw => {
        sw.addEventListener('change', function () {
            const pesertaId = this.dataset.pesertaId;
            const url = this.dataset.url;
            const $label = document.querySelector('.attendance-label-' + pesertaId);
            const $waktu = document.querySelector('.waktu-hadir-' + pesertaId);
            const $avatar = this.closest('.list-group-item').querySelector('.avatar');
            const isChecked = this.checked;

            // Optimistic UI
            $label.textContent = isChecked ? 'Hadir' : 'Tidak Hadir';
            $label.className = `form-check-label attendance-label-${pesertaId} ${isChecked ? 'text-green fw-semibold' : 'text-muted'}`;
            if ($waktu) $waktu.classList.toggle('d-none', !isChecked);
            if ($avatar) {
                $avatar.className = `avatar avatar-sm me-2 rounded-circle ${isChecked ? 'bg-green text-white' : 'bg-secondary-lt text-muted'}`;
            }

            axios.patch(url, { _token: '{{ csrf_token() }}' })
                .then(res => {
                    if (res.data.success && res.data.waktu_hadir && $waktu) {
                        $waktu.childNodes[1] && ($waktu.childNodes[1].textContent = `• Hadir: ${res.data.waktu_hadir}`);
                    }
                })
                .catch(() => {
                    // Revert on failure
                    this.checked = !isChecked;
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memperbarui absensi.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                });
        });
    });

    // ─── Select2 in Modals ────────────────────────────────────
    if (window.loadSelect2) {
        window.loadSelect2().then(() => {
            $('.select2-officials').select2({ theme: 'bootstrap-5', width: '100%', dropdownParent: $('#modal-set-officials') });
            $('.select2-participants').select2({ theme: 'bootstrap-5', width: '100%', placeholder: 'Cari peserta...', dropdownParent: $('#modal-add-participants') });
        });
    }

    // ─── HugeRTE Auto-save (on textarea directly) ─────────────
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

    // ─── Tambah Baris Peserta Luar ────────────────────────────
    const btnAddLuar = document.getElementById('btn-add-luar');
    if (btnAddLuar) {
        btnAddLuar.addEventListener('click', function () {
            const list = document.getElementById('peserta-luar-list');
            const idx = list.querySelectorAll('.peserta-luar-row').length;
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 peserta-luar-row';
            row.innerHTML = `
                <div class="col"><input type="text" name="peserta_luar[${idx}][nama]" class="form-control" placeholder="Nama lengkap"></div>
                <div class="col"><input type="email" name="peserta_luar[${idx}][email]" class="form-control" placeholder="Email"></div>
                <div class="col"><input type="text" name="peserta_luar[${idx}][jabatan]" class="form-control" placeholder="Jabatan/Peran"></div>
                <div class="col-auto d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-ghost-danger btn-remove-luar" title="Hapus baris"><i class="ti ti-x"></i></button>
                </div>`;
            list.appendChild(row);
            row.querySelector('.btn-remove-luar').addEventListener('click', () => row.remove());
        });
    }

    // Reload page after adding participants
    document.addEventListener('ajax-form:success', function () {
        if ($('#modal-add-participants').is(':visible')) {
            setTimeout(() => location.reload(), 500);
        }
        if ($('#modal-add-agenda').length && !$('#modal-add-agenda').hasClass('d-none')) {
            setTimeout(() => location.reload(), 500);
        }
    });
});
</script>
@endpush
