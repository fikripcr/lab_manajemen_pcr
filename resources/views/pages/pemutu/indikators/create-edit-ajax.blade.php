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
    <form action="{{ $route }}" method="POST" class="ajax-form">
        @csrf
        @if($isEdit) @method('PUT') @endif
        <input type="hidden" name="redirect_to" value="{{ old('redirect_to', request('redirect_to', url()->previous())) }}">
        
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tabs-info" class="nav-link active" data-bs-toggle="tab"><i class="ti ti-info-circle me-2"></i>Informasi Umum</a>
                    </li>
                    <li class="nav-item" id="nav-performa" style="{{ $indikator->type === 'performa' && $isEdit ? '' : 'display: none;' }}">
                        <a href="#tabs-performa" class="nav-link" data-bs-toggle="tab"><i class="ti ti-users me-2"></i>Penugasan KPI (Performa)</a>
                    </li>
                    <li class="nav-item" id="nav-target" style="{{ $indikator->type === 'standar' && $isEdit ? '' : 'display: none;' }}">
                        <a href="#tabs-target" class="nav-link" data-bs-toggle="tab"><i class="ti ti-target me-2"></i>Target & Unit Kerja</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- TAB 1: INFORMASI UMUM -->
                    <div class="tab-pane active show" id="tabs-info">
                        <div class="row">
                            <div class="col-md-12">
                                <x-tabler.form-textarea name="indikator" label="Nama Indikator" rows="2" placeholder="Masukkan nama indikator..." value="{{ old('indikator', $indikator->indikator) }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <x-tabler.form-input 
                                    name="no_indikator" 
                                    label="Kode / No Indikator" 
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
                                <label class="form-label">{{ $type->name }}</label>
                                <x-tabler.form-select name="labels" id="label-{{ $type->labeltype_id }}" type="select2" multiple="true" data-placeholder="Pilih {{ $type->name }}...">
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
                            <div class="col-md-12">
                                <x-tabler.form-textarea type="editor" id="keterangan" name="keterangan" label="Definisi / Keterangan" height="300" value="{{ old('keterangan', $indikator->keterangan) }}" />
                            </div>
                        </div>
                    </div>

                    <!-- TAB: HIRARKI -->
                    <!-- TAB: KPI ASSIGN -->
                    <div class="tab-pane" id="tabs-performa">
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
                                    <table class="table table-vcenter table-bordered" id="kpi-repeater-table">
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
                                                            <input type="text" class="form-control mb-2" name="kpi_assign[{{$index}}][target_value]" placeholder="Nilai Target" value="{{ $kpi->target_value }}">
                                                            <input type="text" class="form-control" name="kpi_assign[{{$index}}][unit_ukuran]" placeholder="%, org, dll (Satuan)" value="{{ $kpi->unit_ukuran ?? '' }}">
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
                                
                                <button type="button" class="btn btn-outline-danger btn-sm" id="btn-add-kpi">
                                    <i class="ti ti-plus me-1"></i> Tambah Sasaran
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: TARGET & UNIT -->
                    <div class="tab-pane" id="tabs-target">
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
            </div>
            <div class="card-footer text-end">
                <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy" :text="$isEdit ? 'Update Indikator' : 'Simpan Indikator'" />
            </div>
        </div>
    </form>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelector = document.getElementById('type-selector');
            const navPerforma = document.getElementById('nav-performa');
            const navTarget = document.getElementById('nav-target');
            const parentIdSelector = document.getElementById('parent-id-selector');

            function getCurrentType() {
                if (typeSelector) return typeSelector.value;
                // Fallback: read from hidden input (renop context)
                const hidden = document.querySelector('input[type="hidden"][name="type"]');
                return hidden ? hidden.value : '';
            }

            function toggleTabs() {
                const type = getCurrentType();
                if (!navPerforma || !navTarget || !parentIdSelector) return;
                if (type === 'performa') {
                    // Performa: show performa repeater, hide target
                    navPerforma.style.display = 'block';
                    navTarget.style.display = 'none';
                    parentIdSelector.setAttribute('required', 'required');
                } else if (type === 'standar') {
                    // Standar: show target & unit, hide performa repeater
                    navPerforma.style.display = 'none';
                    navTarget.style.display = 'block';
                    parentIdSelector.removeAttribute('required');
                } else {
                    // Renop (and any other): hide both tabs
                    navPerforma.style.display = 'none';
                    navTarget.style.display = 'none';
                    parentIdSelector.removeAttribute('required');
                }
            }

            if (typeSelector) {
                typeSelector.addEventListener('change', toggleTabs);
            }
            toggleTabs();

            // KPI Assignments Repeater Logic
            const kpiBody = document.getElementById('kpi-repeater-body');
            const btnAddKpi = document.getElementById('btn-add-kpi');
            let kpiIndex = {{ isset($indikator) && $indikator->pegawai ? $indikator->pegawai->count() : 0 }};

            // Options for Select2
            const pegawaiOptions = `
                @foreach($pegawais as $p)
                    <option value="{{ $p->encrypted_pegawai_id }}">{{ $p->nama }}</option>
                @endforeach
            `;

            if (btnAddKpi && kpiBody) {
                btnAddKpi.addEventListener('click', function () {
                    const tr = document.createElement('tr');
                    tr.className = 'kpi-row';
                    tr.innerHTML = `
                        <td>
                            <input type="hidden" name="kpi_assign[${kpiIndex}][selected]" value="1">
                            <select class="form-select select2-kpi" name="kpi_assign[${kpiIndex}][pegawai_id]" required data-placeholder="Pilih pegawai...">
                                ${pegawaiOptions}
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control mb-2" name="kpi_assign[${kpiIndex}][target_value]" placeholder="Nilai Target">
                            <input type="text" class="form-control" name="kpi_assign[${kpiIndex}][unit_ukuran]" placeholder="%, org, dll (Satuan)">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-icon btn-danger btn-sm btn-remove-row" title="Hapus"><i class="ti ti-trash"></i></button>
                        </td>
                    `;
                    kpiBody.appendChild(tr);

                    // Re-init select2 for the new row if function exists globally
                    if (typeof window.initOfflineSelect2 === 'function') {
                        window.initOfflineSelect2();
                    }

                    kpiIndex++;
                });

                kpiBody.addEventListener('click', function (e) {
                    if (e.target.closest('.btn-remove-row')) {
                        e.target.closest('tr').remove();
                    }
                });
            }

            const checkboxes = document.querySelectorAll('.unit-checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const unitId = this.dataset.id;
                    const targetInput = document.getElementById('target-' + unitId);
                    if (this.checked) {
                        targetInput.removeAttribute('disabled');
                    } else {
                        targetInput.setAttribute('disabled', 'disabled');
                        targetInput.value = '';
                    }
                });
            });
        });
    </script>
    @endpush
    @endsection
