@extends('layouts.tabler.app')
@section('title', $pageTitle)

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Detail Rapat: {{ $rapat->judul_kegiatan }}
            </h2>
            <div class="text-muted mt-1">
                {{ $rapat->jenis_rapat }} &bull; {{ formatTanggalIndo($rapat->tgl_rapat) }}
            </div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <x-tabler.button type="back" href="{{ route('Kegiatan.rapat.index') }}" />
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
            <li class="nav-item">
                <a href="#tabs-info" class="nav-link active" data-bs-toggle="tab">Info & Absensi</a>
            </li>
            <li class="nav-item">
                <a href="#tabs-agenda" class="nav-link" data-bs-toggle="tab">Agenda & Pembahasan</a>
            </li>
            <li class="nav-item">
                <a href="#tabs-hasil" class="nav-link" data-bs-toggle="tab">Hasil & Laporan</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            {{-- TAB 1: INFO & ABSENSI --}}
            <div class="tab-pane active show" id="tabs-info">
                {{-- ROW 1: METADATA --}}
                <div class="row row-cards mb-3">
                    <div class="col-md-6">
                        {{-- INFORMASI RAPAT --}}
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Meeting</h3>
                                <div class="card-actions">
                                    <x-tabler.button href="{{ route('Kegiatan.rapat.edit', $rapat) }}" class="btn-primary btn-sm" icon="ti ti-edit" text="Edit" />
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <div class="row">
                                            <div class="col-auto">
                                                <span class="bg-blue-lt avatar shadow-sm">
                                                    <i class="ti ti-calendar-Kegiatan"></i>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">Jadwal & Lokasi</div>
                                                <div class="text-muted small">
                                                    <div class="mb-1">
                                                        <i class="ti ti-clock me-1"></i> {{ $rapat->waktu_mulai->format('H:i') }} - {{ $rapat->waktu_selesai->format('H:i') }}
                                                    </div>
                                                    <div>
                                                        <i class="ti ti-map-pin me-1"></i> {{ $rapat->tempat_rapat }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="row">
                                            <div class="col-auto">
                                                <span class="bg-cyan-lt avatar shadow-sm">
                                                    <i class="ti ti-notes"></i>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">Keterangan</div>
                                                <div class="text-muted small">{{ $rapat->keterangan ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        {{-- PEJABAT RAPAT --}}
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">Pejabat Rapat</h3>
                                <div class="card-actions">
                                    <x-tabler.button 
                                        type="warning" 
                                        class="btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modal-set-officials" 
                                        icon="ti ti-users" 
                                        text="Set Pejabat" 
                                    />
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <span class="avatar avatar shadow-sm rounded-circle">{{ strtoupper(substr($rapat->ketua_user->name ?? '?', 0, 2)) }}</span>
                                            </div>
                                            <div>
                                                <div class="font-weight-medium">Ketua Rapat</div>
                                                <div class="text-muted small">{{ $rapat->ketua_user->name ?? '- Belum Diset -' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <span class="avatar avatar shadow-sm rounded-circle">{{ strtoupper(substr($rapat->notulen_user->name ?? '?', 0, 2)) }}</span>
                                            </div>
                                            <div>
                                                <div class="font-weight-medium">Notulen</div>
                                                <div class="text-muted small">{{ $rapat->notulen_user->name ?? '- Belum Diset -' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ROW 2: ATTENDANCE & ENTITY --}}
                <div class="row row-cards">
                    <div class="col-md-8">
                        {{-- DAFTAR HADIR --}}
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Hadir Peserta</h3>
                                <div class="card-actions">
                                    <x-tabler.button 
                                        type="primary" 
                                        class="btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modal-add-participants" 
                                        icon="ti ti-user-plus" 
                                        text="Tambah Peserta" 
                                    />
                                     <span class="badge bg-green-lt ms-2">{{ $rapat->pesertas->count() }} Orang</span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <form action="{{ route('Kegiatan.rapat.update-attendance', $rapat) }}#tabs-info" method="POST">
                                    @csrf
                                    <div class="card-table">
                                        <x-tabler.datatable-client
                                            id="table-attendance"
                                            :columns="[
                                                ['name' => 'Nama & Jabatan'],
                                                ['name' => 'Status Kehadiran', 'width' => '200px', 'sortable' => false],
                                                ['name' => 'Waktu Hadir', 'width' => '150px', 'sortable' => false]
                                            ]"
                                        >
                                            @foreach($rapat->pesertas as $peserta)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="avatar avatar-xs me-2 rounded-circle bg-light text-muted">{{ strtoupper(substr($peserta->user->name ?? '?', 0, 1)) }}</span>
                                                        <div>
                                                            <div class="font-weight-medium">{{ $peserta->user->name ?? 'User N/A' }}</div>
                                                            <div class="text-muted small">{{ $peserta->jabatan }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select name="attendance[{{ $peserta->rapatpeserta_id }}][status]" class="form-select">
                                                        <option value="" {{ is_null($peserta->status) ? 'selected' : '' }}>- Belum Absen -</option>
                                                        <option value="hadir" {{ $peserta->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                        <option value="izin" {{ $peserta->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                                        <option value="sakit" {{ $peserta->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                                        <option value="alpa" {{ $peserta->status == 'alpa' ? 'selected' : '' }}>Alpa</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="time" name="attendance[{{ $peserta->rapatpeserta_id }}][waktu_hadir]" 
                                                        class="form-control"
                                                        value="{{ $peserta->waktu_hadir ? $peserta->waktu_hadir->format('H:i') : '' }}">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </x-tabler.datatable-client>
                                    </div>
                                    <div class="p-3 bg-light text-end">
                                        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-check" text="Simpan Absensi" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        {{-- ENTITY --}}
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Entitas Terkait</h3>
                                <div class="card-actions">
                                    <x-tabler.button 
                                        type="button" 
                                        class="btn-sm ajax-modal-btn" 
                                        data-url="{{ route('Kegiatan.rapat.entitas.create', $rapat) }}" 
                                        data-modal-title="Tambah Entitas Terkait"
                                        icon="ti ti-plus" 
                                        text="Tambah" 
                                    />
                                </div>
                            </div>
                            <div class="card-body py-2">
                                @if($rapat->entitas->count() > 0)
                                    @foreach($rapat->entitas as $entitas)
                                        <div class="mb-2">
                                            <strong class="small">{{ $entitas->model }}</strong>: <span class="badge bg-light text-dark">{{ $entitas->model_id }}</span>
                                            <div class="text-muted x-small">{{ Str::limit($entitas->keterangan, 50) }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-muted text-center small py-2">Tidak ada entitas terkait.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: AGENDA & PEMBAHASAN --}}
            <div class="tab-pane" id="tabs-agenda">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title mb-0">Agenda & Pembahasan</h3>
                        <p class="text-muted small mb-0">Ketikan isi agenda akan tersimpan secara otomatis.</p>
                    </div>
                    <x-tabler.button type="button" class="btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-agenda" icon="ti ti-plus" text="Tambah Agenda" />
                </div>

                <form id="form-agenda" action="{{ route('Kegiatan.rapat.update-agenda', $rapat) }}" method="POST">
                    @csrf
                    <div class="accordion" id="accordion-agenda">
                        @forelse($rapat->agendas as $index => $agenda)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $agenda->rapatagenda_id }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $agenda->rapatagenda_id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                        <i class="ti ti-checklist me-2 text-muted"></i>
                                        {{ $loop->iteration }}. {{ $agenda->judul_agenda }}
                                        <span class="ms-2 badge bg-blue-lt save-status-{{ $agenda->rapatagenda_id }} d-none">Saving...</span>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $agenda->rapatagenda_id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#accordion-agenda">
                                    <div class="accordion-body">
                                        <label class="form-label">Catatan Pembahasan / Hasil Agenda</label>
                                        <x-tabler.form-textarea 
                                            name="agendas[{{ $agenda->rapatagenda_id }}][isi]" 
                                            class="editor-agenda" 
                                            data-agenda-id="{{ $agenda->rapatagenda_id }}"
                                            :value="$agenda->isi" 
                                            rows="5" />
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 border rounded-2 bg-light">
                                <p class="text-muted mb-0">Belum ada agenda rapat.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-3 text-end d-print-none">
                        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy" text="Simpan Manual" />
                    </div>
                </form>
            </div>

            {{-- TAB 3: HASIL & LAPORAN --}}
            <div class="tab-pane" id="tabs-hasil">
                <div class="card card-body text-center py-5">
                    <h3 class="mb-3">Laporan Hasil Rapat</h3>
                    <p class="text-muted mb-4">
                        Unduh laporan hasil rapat lengkap dalam format PDF, mencakup informasi rapat, daftar hadir, dan hasil pembahasan agenda.
                    </p>
                    <div>
                        <x-tabler.button type="button" class="btn-red btn-lg" href="{{ route('Kegiatan.rapat.generate-pdf', $rapat) }}" icon="ti ti-file-type-pdf" text="Download PDF Hasil Rapat" />
                    </div>
                </div>
            </div>
        </div> {{-- Closing tab-content --}}
    </div> {{-- Closing card-body --}}
</div> {{-- Closing main card --}}
{{-- MODAL: Tambah Agenda --}}
<x-tabler.form-modal 
    id="modal-add-agenda"
    title="Tambah Agenda Rapat" 
    :route="route('Kegiatan.rapat.agenda.store', $rapat) . '#tabs-agenda'"
>
    <x-tabler.form-input name="judul_agenda" label="Judul Agenda" placeholder="Contoh: Pembahasan KPI 2024" required="true" />
</x-tabler.form-modal>

{{-- MODAL: Set Pejabat Rapat --}}
<x-tabler.form-modal 
    id="modal-set-officials"
    title="Set Pejabat Rapat" 
    :route="route('Kegiatan.rapat.update-officials', $rapat) . '#tabs-info'"
>
    <x-tabler.form-select name="ketua_user_id" label="Ketua Rapat" type="select2" required="true" class="select2-modal">
        <option value="" selected disabled>Pilih Ketua Rapat</option>
        @foreach($rapat->pesertas as $peserta)
            <option value="{{ $peserta->user_id }}" {{ $rapat->ketua_user_id == $peserta->user_id ? 'selected' : '' }}>{{ $peserta->user->name }}</option>
        @endforeach
    </x-tabler.form-select>
    <x-tabler.form-select name="notulen_user_id" label="Notulen Rapat" type="select2" required="true" class="select2-modal">
        <option value="" selected disabled>Pilih Notulen Rapat</option>
        @foreach($rapat->pesertas as $peserta)
            <option value="{{ $peserta->user_id }}" {{ $rapat->notulen_user_id == $peserta->user_id ? 'selected' : '' }}>{{ $peserta->user->name }}</option>
        @endforeach
    </x-tabler.form-select>
</x-tabler.form-modal>

{{-- MODAL: Tambah Peserta Rapat --}}
<x-tabler.form-modal 
    id="modal-add-participants"
    title="Tambah Peserta Rapat" 
    :route="route('Kegiatan.rapat.participants.store', $rapat) . '#tabs-info'"
>
    <div class="mb-3">
        <label class="form-label">Pilih Peserta (Bisa pilih banyak)</label>
        <select name="user_ids[]" class="form-select select2-modal-participants" multiple="multiple" data-placeholder="Pilih Peserta..." required>
                @foreach(\App\Models\User::all() as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
        </select>
    </div>
    <x-tabler.form-input name="jabatan" label="Jabatan/Peran (Opsional)" placeholder="Contoh: Peserta, Narasumber, dll" />
</x-tabler.form-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tab persistence
        const hash = window.location.hash;
        if (hash) {
            const tabTarget = document.querySelector('a[href="' + hash + '"]');
            if (tabTarget) {
                const tab = new bootstrap.Tab(tabTarget);
                tab.show();
            }
        }

        // Update hash when tab changes
        const tabLinks = document.querySelectorAll('a[data-bs-toggle="tab"]');
        tabLinks.forEach(link => {
            link.addEventListener('shown.bs.tab', function (e) {
                const href = e.target.getAttribute('href');
                history.replaceState(null, null, href);
            });
        });

        // Accordion persistence
        const activeAccordionId = localStorage.getItem('rapat_active_accordion_{{ $rapat->rapat_id }}');
        if (activeAccordionId) {
            const accordionBtn = document.querySelector('button[data-bs-target="' + activeAccordionId + '"]');
            if (accordionBtn) {
                const collapseElement = document.querySelector(activeAccordionId);
                if (collapseElement) {
                    // Remove 'show' from default active one if it's different
                    document.querySelectorAll('#accordion-agenda .accordion-collapse.show').forEach(el => {
                        if('#' + el.id !== activeAccordionId) el.classList.remove('show');
                    });
                    document.querySelectorAll('#accordion-agenda .accordion-button').forEach(btn => {
                        if(btn.getAttribute('data-bs-target') !== activeAccordionId) btn.classList.add('collapsed');
                    });

                    const bsCollapse = new bootstrap.Collapse(collapseElement, { toggle: false });
                    bsCollapse.show();
                    accordionBtn.classList.remove('collapsed');
                }
            }
        }

        // Save accordion state
        const accordionItems = document.querySelectorAll('#accordion-agenda .accordion-collapse');
        accordionItems.forEach(item => {
            item.addEventListener('shown.bs.collapse', function () {
                localStorage.setItem('rapat_active_accordion_{{ $rapat->rapat_id }}', '#' + item.id);
            });
        });

        // Initialize Select2 in modals
        if (window.loadSelect2) {
            window.loadSelect2().then(() => {
                $('.select2-modal').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $('#modal-set-officials')
                });
                $('.select2-modal-participants').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Pilih Peserta...',
                    dropdownParent: $('#modal-add-participants')
                });
            });
        }

        // Initialize HugeRTE for agenda items
        if (window.loadHugeRTE) {
            window.loadHugeRTE('.editor-agenda', {
                height: 300,
                menubar: false,
                statusbar: false,
                plugins: 'lists',
                toolbar: 'bold italic | bullist numlist',
                setup: function (editor) {
                    let timeout;
                    editor.on('input change keyup', function () {
                        clearTimeout(timeout);
                        const agendaId = editor.getElement().dataset.agendaId;
                        const $status = $('.save-status-' + agendaId);
                        
                        $status.removeClass('d-none bg-green-lt bg-red-lt').addClass('bg-blue-lt').text('Typing...');

                        timeout = setTimeout(() => {
                            autoSaveAgenda(agendaId, editor.getContent());
                        }, 1500); // 1.5 seconds debounce
                    });
                }
            });
        }

        function autoSaveAgenda(agendaId, content) {
            const $status = $('.save-status-' + agendaId);
            $status.removeClass('d-none').text('Saving...').addClass('bg-blue-lt').removeClass('bg-green-lt bg-red-lt');

            const payload = {
                _token: '{{ csrf_token() }}',
                agendas: {}
            };
            payload.agendas[agendaId] = { isi: content };

            axios.post('{{ route('Kegiatan.rapat.update-agenda', $rapat) }}', payload)
                .then(response => {
                    $status.text('Saved').addClass('bg-green-lt').removeClass('bg-blue-lt');
                    setTimeout(() => $status.addClass('d-none'), 2000);
                })
                .catch(error => {
                    console.error('Auto-save error:', error);
                    $status.text('Error!').addClass('bg-red-lt').removeClass('bg-blue-lt');
                });
        }
    });
</script>
@endpush
