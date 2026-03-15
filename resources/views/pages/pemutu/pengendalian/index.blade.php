@extends('layouts.tabler.app')
@section('title', 'Pengendalian Indikator - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Pengendalian Indikator  SPMI {{ $siklus['tahun'] }}" pretitle="Pengendalian">
    <x-slot:actions>
        <div class="nav nav-pills" id="top-tabs" role="tablist">
            <a href="#tab-akademik" class="nav-link active" data-bs-toggle="tab" role="tab">
                <i class="ti ti-school me-2"></i>Akademik
            </a>
            <a href="#tab-non-akademik" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                <i class="ti ti-building-community me-2"></i>Non Akademik
            </a>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="tab-content">
    @foreach(['akademik', 'non_akademik'] as $type)
        @php 
            $periode = $siklus[$type]; 
            $userUnits = ${$type . 'Units'};
            $rapat = ${$type . 'Rapat'};
            $rootDoks = ${$type . 'RootDoks'};
            $typeId = str_replace('_', '-', $type);
        @endphp
        <div class="tab-pane {{ $type == 'akademik' ? 'active show' : '' }}" id="tab-{{ $typeId }}" role="tabpanel">
            @if($periode)
                @php $jadwalTersedia = $periode->pengendalian_awal && $periode->pengendalian_akhir; @endphp
                
                <x-tabler.card>
                    <x-tabler.card-header class="border-bottom-0 pt-4">
                        <ul class="nav nav-pills card-header-pills" id="pengendalian-tabs-{{ $typeId }}" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab-pengendalian-{{ $typeId }}" class="nav-link active" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-settings-check me-2"></i> Pengendalian Standar
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-rtm-{{ $typeId }}" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                                    <i class="ti ti-calendar-event me-2"></i> Rapat Tinjauan Manajemen (RTM)
                                </a>
                            </li>
                        </ul>
                    </x-tabler.card-header>

                    <div class="tab-content">
                        {{-- SUB-TAB: PENGENDALIAN --}}
                        <div class="tab-pane active show" id="tab-pengendalian-{{ $typeId }}" role="tabpanel">
                            <x-tabler.card-body class="border-top">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="mb-1">Periode {{ $periode->jenis_periode }} {{ $periode->periode }}</h3>
                                        <div class="text-muted small">
                                            @if($jadwalTersedia)
                                                <i class="ti ti-calendar me-1"></i>
                                                Jadwal: {{ $periode->pengendalian_awal->format('d M') }} - {{ $periode->pengendalian_akhir->format('d M Y') }}
                                            @else
                                                <span class="text-warning"><i class="ti ti-alert-triangle me-1"></i> Jadwal Belum Diatur</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex gap-2">
                                        <x-tabler.datatable-page-length :dataTableId="'table-pengend-' . $typeId" />
                                        <x-tabler.datatable-filter :dataTableId="'table-pengend-' . $typeId">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <x-tabler.form-select name="unit_id" id="unit_id_{{ $typeId }}" label="Unit / Area" class="unit-filter" placeholder="Filter Area / Unit" :options="$userUnits->pluck('name', 'encrypted_org_unit_id')" type="select2" />
                                                </div>
                                                <div class="col-12">
                                                    <x-tabler.form-select name="dok_id" id="dok_id_{{ $typeId }}" label="Standar / Dokumen" placeholder="Filter Standar" :options="$rootDoks->pluck('judul', 'encrypted_dok_id')" type="select2" />
                                                </div>
                                                <div class="col-12">
                                                    <x-tabler.form-select name="pengend_status" id="pengend_status_{{ $typeId }}" label="Status Pengendalian">
                                                        <option value="all">Semua</option>
                                                        <option value="tetap">Tetap</option>
                                                        <option value="penyesuaian">Penyesuaian</option>
                                                        <option value="nonaktif">Nonaktif</option>
                                                        <option value="empty">Belum Diisi</option>
                                                    </x-tabler.form-select>
                                                </div>
                                                <div class="col-12">
                                                    <x-tabler.form-select name="pengend_important_matrix" id="pengend_important_matrix_{{ $typeId }}" label="Kepentingan">
                                                        <option value="all">Semua</option>
                                                        <option value="important">Important</option>
                                                        <option value="not_important">Not Important</option>
                                                    </x-tabler.form-select>
                                                </div>
                                                <div class="col-12">
                                                    <x-tabler.form-select name="pengend_urgent_matrix" id="pengend_urgent_matrix_{{ $typeId }}" label="Urgensi">
                                                        <option value="all">Semua</option>
                                                        <option value="urgent">Urgent</option>
                                                        <option value="not_urgent">Not Urgent</option>
                                                    </x-tabler.form-select>
                                                </div>
                                            </div>
                                        </x-tabler.datatable-filter>
                                        <x-tabler.datatable-search :dataTableId="'table-pengend-' . $typeId" />
                                    </div>
                                </div>
                            </x-tabler.card-body>
                            <div class="table-responsive border-top">
                                <x-tabler.datatable
                                    id="table-pengend-{{ $typeId }}"
                                    route="{{ route('pemutu.pengendalian.data', $periode->encrypted_periodespmi_id) }}"
                                    :columns="[
                                        ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator'],
                                        ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%'],
                                        ['data' => 'analisis', 'name' => 'analisis', 'title' => 'Analisis', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'status_ami', 'name' => 'status_ami', 'title' => 'AMI', 'width' => '8%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'status_pengend', 'name' => 'status_pengend', 'title' => 'Status', 'width' => '9%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'eisenhower_matrix', 'name' => 'eisenhower_matrix', 'title' => 'Matrix', 'width' => '9%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '7%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                    ]"
                                />
                            </div>
                        </div>

                        {{-- SUB-TAB: RTM --}}
                        <div class="tab-pane" id="tab-rtm-{{ $typeId }}" role="tabpanel">
                            @if(!$rapat)
                                <x-tabler.card-body class="text-center py-5 border-top">
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
                                </x-tabler.card-body>
                            @else
                                <x-tabler.card-body class="border-top">
                                    @include('pages.pemutu.pengendalian._rtm_index_content', ['rapat' => $rapat, 'periode' => $periode, 'typeId' => $typeId])
                                </x-tabler.card-body>
                            @endif
                        </div>
                    </div>
                </x-tabler.card>
            @else
                <x-tabler.card>
                    <x-tabler.card-body class="py-5">
                        <x-tabler.empty-state 
                            title="Periode Belum Tersedia" 
                            text="Data periode {{ str_replace('_', ' ', $type) }} untuk tahun {{ $siklus['tahun'] }} belum dibuat."
                            icon="ti ti-calendar-off" 
                        />
                    </x-tabler.card-body>
                </x-tabler.card>
            @endif
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Eisenhower Matrix inline AJAX
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

    // Attendance Switch Toggle
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('attendance-switch')) return;
        
        const sw = e.target;
        const pesertaId = sw.dataset.pesertaId;
        const url = sw.dataset.url;
        const $label = document.querySelector('.attendance-label-' + pesertaId);
        const $waktu = document.querySelector('.waktu-hadir-' + pesertaId);
        const $avatar = document.querySelector('#avatar-' + pesertaId);
        const isChecked = sw.checked;

        // Optimistic UI
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

    // Row management for participants
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

