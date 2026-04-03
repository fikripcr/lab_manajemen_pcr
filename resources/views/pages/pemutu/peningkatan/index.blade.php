@extends('layouts.tabler.app')
@section('title', 'Peningkatan Indikator - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Peningkatan Indikator SPMI {{ $siklus['tahun'] }}" pretitle="Peningkatan">
    <x-slot:actions>
        <x-pemutu.kelompok-selector :active-kelompok="$activeKelompok" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    @if($periode)
        <x-pemutu.active-period :periode="$periode" type="peningkatan" />
        
        <x-tabler.card>
            <x-tabler.card-header class="border-bottom px-4 pt-3 pb-3 d-flex justify-content-between align-items-center">
                <ul class="nav nav-pills card-header-pills" id="peningkatan-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tab-rtm" class="nav-link active" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-calendar-event me-2"></i> Rapat Peningkatan (RTM)
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-duplikasi" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                            <i class="ti ti-copy me-2"></i> Duplikasi Standar
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-review" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                            <i class="ti ti-settings-2 me-2"></i> Review Indikator
                        </a>
                    </li>
                </ul>
                <div class="card-actions mb-0" id="review-toolbar" style="display: none;">
                    <div class="d-flex gap-2">
                        <x-tabler.datatable-page-length dataTableId="table-review" />
                        <x-tabler.datatable-search dataTableId="table-review" />
                        <x-tabler.datatable-filter dataTableId="table-review" type="button" target="#table-review-filter-area" />
                    </div>
                </div>
            </x-tabler.card-header>

            <div class="tab-content">
                {{-- SUB-TAB: REVIEW --}}
                <div class="tab-pane" id="tab-review" role="tabpanel">
                    <div class="collapse" id="table-review-filter-area">
                        <x-tabler.datatable-filter dataTableId="table-review" type="bare">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <x-tabler.form-select name="unit_id" id="unit_id" label="Unit / Area" placeholder="">
                                        <option value="all">Semua Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="dok_id" id="dok_id" label="Standar / Dokumen" placeholder="">
                                        <option value="all">Semua Standar</option>
                                        @foreach($rootDoks as $dok)
                                            <option value="{{ $dok->encrypted_dok_id }}">{{ $dok->judul }}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                    <div class="col-md-4">
                                        <x-tabler.form-select name="pengend_status" id="pengend_status" label="Status Thn Lalu" placeholder="">
                                            <option value="all">Semua</option>
                                            <option value="tetap">Tetap</option>
                                            <option value="penyesuaian">Penyesuaian</option>
                                            <option value="nonaktif">Nonaktif</option>
                                            <option value="filled">Sudah Review</option>
                                            <option value="empty">Belum Review</option>
                                        </x-tabler.form-select>
                                    </div>
                                </div>
                            </x-tabler.datatable-filter>
                        </div>
                        
                        @if(!$hasDuplicated)
                            <x-tabler.card-body class="text-center py-5 border-top">
                                <span class="avatar avatar-xl rounded bg-yellow-lt mb-3">
                                    <i class="ti ti-alert-triangle fs-1"></i>
                                </span>
                                <h3>Duplikasi Belum Dilakukan</h3>
                                <p class="text-muted">Lakukan duplikasi standar terlebih dahulu di sub-tab <strong>"Duplikasi Standar"</strong> sebelum melakukan review.</p>
                            </x-tabler.card-body>
                        @else
                            {{-- Staging Status Banner --}}
                            @if($stagingCount > 0)
                                <x-tabler.card-body class="border-top py-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="status-dot status-dot-animated bg-warning"></span>
                                            <span class="text-warning fw-semibold">
                                                <i class="ti ti-eye-off me-1"></i>
                                                {{ $stagingCount }} standar masih dalam mode <strong>Staging</strong> — belum tampil di modul ED, AMI, dan Pengendalian.
                                            </span>
                                        </div>
                                        <form method="POST" action="{{ route('pemutu.peningkatan.approve-staging', $periode->encrypted_periodespmi_id) }}" 
                                              onsubmit="return confirm('Yakin ingin mempublish semua standar staging? Indikator akan langsung tampil di modul ED, AMI, dan Pengendalian.')">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="ti ti-check me-1"></i> Final Approve &amp; Publish
                                            </button>
                                        </form>
                                    </div>
                                </x-tabler.card-body>
                            @endif

                            <div class="table-responsive border-top">
                                <x-tabler.datatable
                                    id="table-review"
                                    route="{{ route('pemutu.peningkatan.review-data', $periode->encrypted_periodespmi_id) }}"
                                    :columns="[
                                        ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator'],
                                        ['data' => 'target', 'name' => 'target', 'title' => 'Target Baru', 'width' => '10%', 'class' => 'text-center'],
                                        ['data' => 'status_badge', 'name' => 'status_badge', 'title' => 'Target & Status Thn Lalu', 'width' => '15%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'keterangan_perubahan', 'name' => 'keterangan_perubahan', 'title' => 'Keterangan Perubahan', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'action', 'name' => 'action', 'title' => '', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false]
                                    ]"
                                />
                            </div>
                        @endif
                    </div>

                    {{-- SUB-TAB: DUPLIKASI --}}
                    <div class="tab-pane" id="tab-duplikasi" role="tabpanel">
                        <x-tabler.card-body class="border-top">
                            @include('pages.pemutu.peningkatan._duplikasi_index_content', ['periode' => $periode])
                        </x-tabler.card-body>
                    </div>

                    {{-- SUB-TAB: RTM --}}
                    <div class="tab-pane active show" id="tab-rtm" role="tabpanel">
                        @if(!$rapat)
                            <x-tabler.card-body class="text-center py-5 border-top">
                                <div class="mb-3">
                                    <span class="avatar avatar-xl rounded bg-blue-lt">
                                        <i class="ti ti-calendar-plus fs-1"></i>
                                    </span>
                                </div>
                                <h3>Belum Ada RTM Peningkatan</h3>
                                <p class="text-muted">Buat Rapat Tinjauan Manajemen untuk memulai proses peningkatan periode ini.</p>
                                @php 
                                    $rtmAgendas = 'Rangkuman,Penggunaan Budget';
                                    $rtmUrl = route('Kegiatan.rapat.create', [
                                        'jenis_rapat' => 'RTM Peningkatan',
                                        'entitas_type' => 'PeriodeSpmi',
                                        'entitas_id' => $periode->encrypted_periodespmi_id,
                                        'pre_agendas' => $rtmAgendas
                                    ]);
                                @endphp
                                <x-tabler.button type="create" class="ajax-modal-btn"
                                    data-url="{{ $rtmUrl }}"
                                    data-modal-title="Buat RTM Peningkatan"
                                    data-modal-size="modal-xl"
                                    text="Buat RTM" />
                            </x-tabler.card-body>
                        @else
                            <x-tabler.card-body class="border-top">
                                @include('pages.pemutu.peningkatan._rtm_index_content', ['rapat' => $rapat, 'periode' => $periode])
                            </x-tabler.card-body>
                        @endif
                    </div>
                </div>
            </x-tabler.card>
        @else
            <x-tabler.card>
                <x-tabler.card-body class="py-5 text-center">
                    <x-tabler.empty-state 
                        title="Periode Belum Tersedia" 
                        text="Data periode {{ str_replace('_', ' ', $activeKelompok) }} untuk tahun {{ $siklus['tahun'] }} belum dibuat."
                        icon="ti ti-calendar-off" 
                    />
                </x-tabler.card-body>
            </x-tabler.card>
        @endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const reviewToolbar = document.getElementById('review-toolbar');
    document.querySelectorAll('#peningkatan-tabs .nav-link').forEach(t => {
        t.addEventListener('shown.bs.tab', function(e) {
            if (e.target.getAttribute('href') === '#tab-review') {
                reviewToolbar.style.display = 'block';
            } else {
                reviewToolbar.style.display = 'none';
            }
        });
    });

    window.switchSubTab = function(targetSelector) {
        const triggerEl = document.querySelector(`a[href="${targetSelector}"]`);
        if (triggerEl) {
            const tab = bootstrap.Tab.getOrCreateInstance(triggerEl);
            tab.show();
        }
    };

    // Shared Attendance Toggle
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('attendance-switch')) return;
        
        const sw = e.target;
        const pesertaId = sw.dataset.pesertaId;
        const url = sw.dataset.url;
        const $label = document.querySelector('.attendance-label-' + pesertaId);
        const $waktu = document.querySelector('.waktu-hadir-' + pesertaId);
        const $avatar = document.querySelector('#avatar-' + pesertaId);
        const isChecked = sw.checked;

        if ($label) {
            $label.textContent = isChecked ? 'Hadir' : 'Absen';
            $label.className = `form-check-label attendance-label-${pesertaId} ${isChecked ? 'text-green fw-semibold' : 'text-muted'}`;
        }
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
                sw.checked = !isChecked;
                showErrorMessage('Gagal', 'Gagal memperbarui absensi.');
            });
    });

    // HugeRTE for Agenda
    if (window.loadHugeRTE) {
        window.loadHugeRTE('textarea[data-agenda-id]', {
            height: 250,
            menubar: false,
            statusbar: false,
            plugins: 'lists link table',
            toolbar: 'bold italic underline | bullist numlist | link | table | undo redo',
            setup: function (editor) {
                let timeout;
                editor.on('input change keyup', function () {
                    clearTimeout(timeout);
                    const agendaId = editor.getElement().dataset.agendaId;
                    const rapatId = editor.getElement().dataset.rapatId;
                    const $s = document.querySelector('.save-status-' + agendaId);
                    if ($s) { $s.classList.remove('d-none', 'bg-green-lt', 'bg-red-lt'); $s.classList.add('bg-blue-lt'); $s.textContent = 'Typing...'; }
                    timeout = setTimeout(() => autoSaveAgenda(agendaId, rapatId, editor.getContent()), 1500);
                });
            }
        });
    }

    function autoSaveAgenda(agendaId, rapatId, content) {
        const $s = document.querySelector('.save-status-' + agendaId);
        if ($s) { $s.classList.remove('d-none', 'bg-green-lt', 'bg-red-lt'); $s.classList.add('bg-blue-lt'); $s.textContent = 'Saving...'; }
        
        axios.post('{{ url("kegiatan/rapat/update-agenda") }}/' + rapatId, {
            _token: '{{ csrf_token() }}',
            agendas: { [agendaId]: { isi: content } }
        })
        .then(() => {
            if ($s) { $s.textContent = 'Saved ✓'; $s.classList.replace('bg-blue-lt', 'bg-green-lt'); setTimeout(() => $s.classList.add('d-none'), 2000); }
        })
        .catch(() => {
            if ($s) { $s.textContent = 'Error ✗'; $s.classList.replace('bg-blue-lt', 'bg-red-lt'); }
        });
    }

    // Participants Row Management
    document.addEventListener('click', function(e) {
        if (e.target.id === 'btn-add-peserta-row') {
            const container = e.target.closest('form').querySelector('.peserta-rows-container');
            const firstRow = container.querySelector('.peserta-row');
            const newRow = firstRow.cloneNode(true);
            const idx = container.querySelectorAll('.peserta-row').length;

            newRow.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/participants\[\d+\]/, `participants[${idx}]`);
                if (el.tagName === 'SELECT') el.selectedIndex = 0;
                if (el.tagName === 'INPUT') el.value = '';
            });

            const removeBtn = newRow.querySelector('.remove-peserta-row');
            if (removeBtn) removeBtn.classList.remove('d-none');

            container.appendChild(newRow);
        }

        if (e.target.closest('.remove-peserta-row')) {
            e.target.closest('.peserta-row').remove();
        }
    });

    document.addEventListener('ajax-form:success', function () {
        setTimeout(() => location.reload(), 500);
    });
});
</script>
@endpush
