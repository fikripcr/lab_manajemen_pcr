@php
    $isEdit = $indikator->exists;
    $title = $isEdit ? 'Edit Indikator' : 'Tambah Indikator Baru';
    $route = $isEdit ? route('pemutu.indikators.update', $indikator) : route('pemutu.indikators.store');
    $method = $isEdit ? 'PUT' : 'POST';

    // Determine context for pre-selection (Create case)
    if (!$isEdit && isset($parentDok)) {
        $selectedDokSubs = $selectedDokSubs ?? [];
    } else {
        $selectedDokSubs = $indikator->dokSubs->pluck('encrypted_doksub_id')->toArray();
    }

    $assignedMap = $isEdit ? $indikator->orgUnits->keyBy('orgunit_id') : collect([]);
@endphp


    @extends('layouts.tabler.app')

    @section('header')
    <x-tabler.page-header :title="$title" pretitle="SPMI / Indikator">
        <x-slot:actions>
            <x-tabler.button href="javascript:history.back()" class="btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" />
        </x-slot:actions>
    </x-tabler.page-header>
    @endsection

    @section('content')
    <form action="{{ $route }}" method="POST" class="ajax-form" novalidate>
        @csrf
        @if($isEdit) @method('PUT') @endif
        <input type="hidden" name="redirect_to" value="{{ old('redirect_to', request('redirect_to', url()->previous())) }}">

        <div class="row row-cards">
            <!-- INFORMASI UMUM & SKALA (KIRI) -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header border-bottom-0">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                            <li class="nav-item">
                                <a href="#tab-informasi-umum" class="nav-link active" data-bs-toggle="tab">
                                    <i class="ti ti-info-circle me-2"></i>Informasi Umum
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-penilaian-skala" class="nav-link" data-bs-toggle="tab">
                                    <i class="ti ti-list-numbers me-2"></i>Penilaian Skala
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- TAB: INFORMASI UMUM -->
                            <div class="tab-pane active show" id="tab-informasi-umum">
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-tabler.form-textarea name="indikator" label="Nama Indikator" rows="2" placeholder="Masukkan nama indikator..." value="{{ old('indikator', $indikator->indikator) }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        {{-- Tipe Indikator Field --}}
                                        @if(isset($parentDok) && !$isEdit && ($isRenopContext ?? false))
                                            <input type="hidden" name="type" value="renop">
                                            <label class="form-label">Tipe Indikator</label>
                                            <div class="form-control-plaintext fw-bold text-primary"><i class="ti ti-lock me-2"></i>Indikator Renop</div>
                                            <div class="form-hint text-muted small">Tipe dikunci karena menambah dari context Poin Renop.</div>
                                        @else
                                            <x-tabler.form-select
                                                id="type-selector"
                                                name="type"
                                                label="Tipe Indikator"
                                                :options="[
                                                    'renop' => 'Indikator Renop',
                                                    'standar' => 'Indikator Standar',
                                                    'performa' => 'Indikator Performa (KPI)'
                                                ]"
                                                :selected="old('type', $indikator->type ?? request('type'))"
                                                :readonly="isset($parentDok) && !$isEdit"
                                            />
                                            @if(isset($parentDok) && !$isEdit)
                                                <input type="hidden" name="type" value="{{ request('type') }}">
                                                <div class="form-hint text-success small">Tipe dikunci karena menambah dari context dokumen.</div>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <x-tabler.form-select
                                            name="kelompok_indikator"
                                            label="Kelompok Indikator"
                                            :required="true"
                                            :options="[
                                                'Akademik' => 'Akademik',
                                                'Non Akademik' => 'Non Akademik'
                                            ]"
                                            :selected="old('kelompok_indikator', $indikator->kelompok_indikator)"
                                        />
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <x-tabler.form-input
                                            name="no_indikator"
                                            label="No Indikator"
                                            type="text"
                                            value="{{ old('no_indikator', $indikator->no_indikator) }}"
                                            placeholder="cth: IND.01"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <x-tabler.form-select name="doksub_ids" label="Dokumen Penjaminan Mutu Terkait" type="select2" multiple="true" data-placeholder="Pilih satu atau lebih dokumen...">
                                            @foreach($dokumens as $doc)
                                                @if($doc->dokSubs->count() > 0)
                                                    <optgroup label="[{{ strtoupper($doc->jenis) }}] {{ $doc->judul }}">
                                                        @foreach($doc->dokSubs as $ds)
                                                            <option value="{{ $ds->encrypted_doksub_id }}" {{ in_array($ds->encrypted_doksub_id, $selectedDokSubs ?? []) ? 'selected' : '' }}>{{ $ds->judul }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        </x-tabler.form-select>
                                    </div>
                                </div>

                                <div class="row">
                                    @foreach($labelTypes as $type)
                                    <div class="col-md-4 mb-1">
                                        <x-tabler.form-select name="labels" id="label-{{ $type->labeltype_id }}" type="select2" label="{{ $type->name }}" multiple="true" data-placeholder="Pilih {{ $type->name }}...">
                                            @php
                                                $selectedLabelIds = $isEdit ? $indikator->labels->where('type_id', $type->labeltype_id)->pluck('label_id')->toArray() : [];
                                            @endphp
                                            @foreach($type->labels as $label)
                                                <option value="{{ $label->encrypted_label_id }}" {{ in_array($label->label_id, $selectedLabelIds) ? 'selected' : '' }}>{{ $label->name }}</option>
                                            @endforeach
                                        </x-tabler.form-select>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <x-tabler.form-textarea id="keterangan" name="keterangan" label="Definisi / Keterangan" height="300" :value="old('keterangan', $indikator->keterangan)" />
                                    </div>
                                </div>
                            </div>
                            <!-- END TAB: INFORMASI UMUM -->

                            <!-- TAB: PENILAIAN SKALA -->
                            <div class="tab-pane" id="tab-penilaian-skala">
                                @php
                                    $hasSkala = false;
                                    if ($indikator->skala && is_array($indikator->skala)) {
                                        foreach($indikator->skala as $val) {
                                            if (!empty(trim(strip_tags($val)))) {
                                                $hasSkala = true;
                                                break;
                                            }
                                        }
                                    } elseif (old('enable_skala')) {
                                        $hasSkala = true;
                                    }
                                @endphp

                                <div class="mb-3">
                                    <label class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="enable_skala" name="enable_skala" value="1" {{ $hasSkala ? 'checked' : '' }}>
                                        <span class="form-check-label fw-bold text-primary">Aktifkan Penilaian Skala Khusus</span>
                                    </label>
                                    <div class="text-muted small mt-1">Definisikan deskripsi untuk setiap level skala penilaian (0 – 4). Centang opsi ini jika Indikator ini memerlukan kriteria kustom. Skala ini akan otomatis ditampilkan kepada auditee saat mengisi Evaluasi Diri. JIKA TIDAK DICENTANG, nilai skala akan dikosongkan.</div>
                                </div>

                                <div id="skala-container" style="{{ $hasSkala ? '' : 'display: none;' }}">
                                    <div class="row g-2">
                                    @foreach([0,1,2,3,4] as $level)
                                    <div class="col-12 mb-2">
                                        <div class="card card-sm border-blue-lt mb-0">
                                            <div class="card-header bg-blue-lt py-2">
                                                <h4 class="card-title text-blue mb-0">Level Skala {{ $level }}</h4>
                                            </div>
                                            <div class="card-body p-2">
                                                <x-tabler.form-textarea
                                                    id="skala-{{ $level }}"
                                                    name="skala[{{ $level }}]"
                                                    label=""
                                                    height="180"
                                                    :value="old('skala.' . $level, ($indikator->skala[$level] ?? ($indikator->skala ? ($indikator->skala[$level] ?? '') : '')))"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- END TAB: PENILAIAN SKALA -->
                        </div>
                    </div>
                </div>
            </div>{{-- end col-lg-7 --}}

            <!-- ASSIGNMENTS (KANAN) -->
            <div class="col-lg-5">
                <!-- CARD: KPI ASSIGN -->
                <div class="card" id="card-performa" style="{{ $indikator->type === 'performa' && $isEdit ? '' : 'display: none;' }}">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-users me-2"></i>Penugasan KPI (Performa)</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <x-tabler.form-select name="parent_id" id="parent-id-selector" label="Indikator Standar Terkait (Induk)" type="select2">
                                    <option value="">-- Pilih Indikator Standar --</option>
                                    @foreach($parents as $p)
                                        <option value="{{ $p->encrypted_indikator_id }}" {{ $p->indikator_id == $indikator->parent_id ? 'selected' : '' }}>
                                            [{{ $p->no_indikator }}] {{ \Str::limit($p->indikator, 150) }}
                                        </option>
                                    @endforeach
                                </x-tabler.form-select>
                                <div class="form-hint">Indikator Performa HARUS merujuk pada satu Indikator Standar.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label required">Daftar Sasaran Kinerja Pegawai</label>
                                <div class="table-responsive border rounded mb-3">
                                    <table class="table table-vcenter border-bottom" id="kpi-repeater-table">
                                        <thead>
                                            <tr>
                                                <th width="50%">Penanggung Jawab <span class="text-danger">*</span></th>
                                                <th width="45%">Target & Satuan</th>
                                                <th width="5%" class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="kpi-repeater-body">
                                            @php
                                                $pegawaiAssigned = $isEdit ? $indikator->pegawai : collect([]);
                                            @endphp
                                            @if($pegawaiAssigned->count() > 0)
                                                @foreach($pegawaiAssigned as $index => $kpi)
                                                    <tr class="kpi-row">
                                                        <td>
                                                            <input type="hidden" name="kpi_assign[{{$index}}][selected]" value="1">
                                                            <select class="form-select select2-kpi" name="kpi_assign[{{$index}}][pegawai_id]" required data-placeholder="Pilih pegawai...">
                                                                <option value="">Pilih pegawai...</option>
                                                                @foreach($pegawais as $p)
                                                                    <option value="{{ $p->encrypted_pegawai_id }}" {{ $p->pegawai_id == $kpi->pegawai_id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <x-tabler.form-input class="mb-2" name="kpi_assign[{{$index}}][target_value]" placeholder="Nilai Target" value="{{ $kpi->target_value }}" />
                                                            <x-tabler.form-input name="kpi_assign[{{$index}}][unit_ukuran]" placeholder="%, org, dll (Satuan)" value="{{ $kpi->unit_ukuran ?? '' }}" />
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-icon btn-danger btn-sm btn-remove-row" title="Hapus"><i class="ti ti-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" class="btn btn-outline-danger btn-sm w-100" id="btn-add-kpi">
                                    <i class="ti ti-plus me-1"></i> Tambah Sasaran
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD: TARGET & UNIT -->
                <div class="card" id="card-target" style="{{ $indikator->type === 'standar' && $isEdit ? '' : 'display: none;' }}">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-target me-2"></i>Target & Unit Kerja</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label required">Unit Kerja Penanggung Jawab & Target</label>
                                <div class="table-responsive border rounded" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="50%">Unit</th>
                                                <th width="50%">Target Unit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                if(!function_exists('renderUnitRow')){
                                                    function renderUnitRow($unit, $level = 0, $assignedMap) {
                                                        $padding = $level * 20;
                                                        $isBold = $level < 2 ? 'fw-bold' : '';
                                                        $bg = '';

                                                        $isChecked = $assignedMap->has($unit->orgunit_id);
                                                        $targetVal = $isChecked ? $assignedMap->get($unit->orgunit_id)->pivot->target : '';
                                                        $isDisabled = !$isChecked ? 'disabled' : '';

                                                        echo '<tr class="'.$bg.'">';
                                                        echo '<td>';
                                                        echo '<div style="padding-left: '.$padding.'px">';
                                                        echo '<label class="form-check form-check-inline mb-0">';
                                                        echo '<input class="form-check-input unit-checkbox" type="checkbox" name="assignments['.$unit->encrypted_org_unit_id.'][selected]" value="1" data-id="'.$unit->encrypted_org_unit_id.'" '.($isChecked ? 'checked' : '').'>';
                                                        echo '<span class="form-check-label '.$isBold.'">'.$unit->name.'</span>';
                                                        echo '</label>';
                                                        echo '</div>';
                                                        echo '</td>';
                                                        echo '<td>';
                                                        echo '<input type="text" class="form-control form-control-sm" name="assignments['.$unit->encrypted_org_unit_id.'][target]" id="target-'.$unit->encrypted_org_unit_id.'" placeholder="Target..." value="'.$targetVal.'" '.$isDisabled.'>';
                                                        echo '</td>';
                                                        echo '</tr>';

                                                        if ($unit->children && $unit->children->count()) {
                                                            foreach($unit->children as $child) {
                                                                renderUnitRow($child, $level + 1, $assignedMap);
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @foreach($orgUnits as $rootUnit)
                                                {{ renderUnitRow($rootUnit, 0, $assignedMap) }}
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button moved to bottom of right col or as a separate card -->
                <div class="card mt-3">
                    <div class="card-body">
                        <x-tabler.button type="submit" class="btn-primary w-100" icon="ti ti-device-floppy" :text="$isEdit ? 'Update Indikator' : 'Simpan Indikator'" />
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endsection
    @push('scripts')
    <script type="module">
    // Logic form ada di resources/js/helpers/pemutu-indikator.js
    window.initPemutuIndikatorForm({
        kpiInitialIndex: {{ isset($indikator) && $indikator->pegawai ? $indikator->pegawai->count() : 0 }},
        pegawaiOptionsHtml: `@foreach($pegawais as $p)<option value="{{ $p->encrypted_pegawai_id }}">{{ $p->nama }}</option>@endforeach`
    });

    if (window.loadHugeRTE) {
        window.loadHugeRTE('#keterangan', { 
            height: 200,
            menubar: false,
            statusbar: false,
            plugins: 'lists',
            toolbar: 'bold italic | outdent indent | bullist numlist'
        });
        window.loadHugeRTE('textarea.form-control[id^="skala-"]', { 
            height: 180,
            menubar: false,
            statusbar: false,
            plugins: 'lists',
            toolbar: 'bold italic | outdent indent | bullist numlist'
        });
    }

    // Toggle Skala Logic
    document.getElementById('enable_skala').addEventListener('change', function() {
        const container = document.getElementById('skala-container');
        if (this.checked) {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
            // Clear content via HugeRTE API if initialized
            if (window.hugerte) {
                [0, 1, 2, 3, 4].forEach(level => {
                    const editor = window.hugerte.get('skala-' + level);
                    if (editor) {
                        editor.setContent('');
                    } else {
                        // fallback clear textarea value directly
                        const textarea = document.getElementById('skala-' + level);
                        if (textarea) textarea.value = '';
                    }
                });
            }
        }
    });
    </script>
    @endpush
