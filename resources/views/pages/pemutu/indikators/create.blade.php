@if(request()->ajax() || request()->has('ajax'))
    <x-tabler.form-modal
        title="Tambah Indikator Baru"
        route="{{ route('pemutu.indikators.store') }}"
        method="POST"
        size="modal-xl"
        submitText="Simpan Indikator"
    >
        <div class="mb-3">
            <ul class="nav nav-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#modal-tabs-info" class="nav-link active" data-bs-toggle="tab"><i class="ti ti-info-circle me-2"></i>Informasi Umum</a>
                </li>
                <li class="nav-item" id="modal-nav-hierarchy" style="display: none;">
                    <a href="#modal-tabs-hierarchy" class="nav-link" data-bs-toggle="tab"><i class="ti ti-hierarchy-2 me-2"></i>Struktur Hirarkis</a>
                </li>
                <li class="nav-item" id="modal-nav-target">
                    <a href="#modal-tabs-target" class="nav-link" data-bs-toggle="tab"><i class="ti ti-target me-2"></i>Target & Unit Kerja</a>
                </li>
                <li class="nav-item">
                    <a href="#modal-tabs-lainnya" class="nav-link" data-bs-toggle="tab"><i class="ti ti-tags me-2"></i>Label & Kategori</a>
                </li>
            </ul>
        </div>

        <div class="tab-content">
            <!-- TAB 1: INFORMASI UMUM -->
            <div class="tab-pane active show" id="modal-tabs-info">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-tabler.form-select 
                            id="modal-type-selector"
                            name="type" 
                            label="Tipe Indikator" 
                            :options="[
                                'renop' => 'Indikator Renop',
                                'standar' => 'Indikator Standar',
                                'performa' => 'Indikator Performa (KPI)'
                            ]"
                            :selected="old('type', request('type'))" 
                            required="true" 
                            :readonly="isset($parentDok)"
                        />
                        @if(isset($parentDok))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-tabler.form-input 
                            name="no_indikator" 
                            label="Kode / No Indikator" 
                            type="text" 
                            value="{{ old('no_indikator') }}"
                            placeholder="cth: IND.01" 
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <x-tabler.form-select name="doksub_ids" label="Dokumen Penjaminan Mutu Terkait" required="true" type="select2" multiple="true" data-placeholder="Pilih satu atau lebih dokumen...">
                            @foreach($dokumens as $doc)
                                @if($doc->dokSubs->count() > 0)
                                    <optgroup label="[{{ strtoupper($doc->jenis) }}] {{ $doc->judul }}">
                                        @foreach($doc->dokSubs as $ds)
                                            <option value="{{ $ds->doksub_id }}" {{ in_array($ds->doksub_id, $selectedDokSubs ?? []) ? 'selected' : '' }}>{{ $ds->judul }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </x-tabler.form-select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <x-tabler.form-textarea name="indikator" label="Nama Indikator" rows="3" placeholder="Masukkan nama indikator..." required="true" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <x-tabler.form-textarea type="editor" id="modal-keterangan-editor" name="keterangan" label="Definisi / Keterangan" height="300" />
                    </div>
                </div>
            </div>

            <!-- TAB: HIRARKI -->
            <div class="tab-pane" id="modal-tabs-hierarchy">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <x-tabler.form-select name="parent_id" id="modal-parent-id-selector" label="Indikator Induk" required="true" type="select2">
                            <option value="">-- Pilih Indikator Standar --</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->indikator_id }}">[{{ $p->no_indikator }}] {{ \Str::limit($p->indikator, 150) }}</option>
                            @endforeach
                        </x-tabler.form-select>
                        <div class="form-hint">Indikator Performa HARUS merujuk pada satu Indikator Standar.</div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: TARGET & UNIT -->
            <div class="tab-pane" id="modal-tabs-target">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label required">Unit Kerja Penanggung Jawab & Target</label>
                        <div class="table-responsive border rounded" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th width="50%">Unit</th>
                                        <th width="50%">Target Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        if(!function_exists('renderUnitRowModal')){
                                            function renderUnitRowModal($unit, $level = 0) {
                                                $padding = $level * 20;
                                                $isBold = $level < 2 ? 'fw-bold' : '';
                                                $bg = ''; 
                                                
                                                echo '<tr class="'.$bg.'">';
                                                echo '<td>';
                                                echo '<div style="padding-left: '.$padding.'px">';
                                                echo '<label class="form-check form-check-inline mb-0">';
                                                echo '<input class="form-check-input modal-unit-checkbox" type="checkbox" name="assignments['.$unit->orgunit_id.'][selected]" value="1" data-id="'.$unit->orgunit_id.'">';
                                                echo '<span class="form-check-label '.$isBold.'">'.$unit->name.'</span>';
                                                echo '</label>';
                                                echo '</div>';
                                                echo '</td>';
                                                echo '<td>';
                                                echo '<input type="text" class="form-control form-control-sm" name="assignments['.$unit->orgunit_id.'][target]" id="modal-target-'.$unit->orgunit_id.'" placeholder="Target..." disabled>';
                                                echo '</td>';
                                                echo '</tr>';

                                                if ($unit->children && $unit->children->count()) {
                                                    foreach($unit->children as $child) {
                                                        renderUnitRowModal($child, $level + 1);
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    @foreach($orgUnits as $rootUnit)
                                        {{ renderUnitRowModal($rootUnit) }}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: LABEL & LAINNYA -->
            <div class="tab-pane" id="modal-tabs-lainnya">
                <div class="row">
                    @foreach($labelTypes as $type)
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ $type->name }}</label>
                        <x-tabler.form-select name="labels" id="modal-label-{{ $type->labeltype_id }}" type="select2" multiple="true" data-placeholder="Pilih {{ $type->name }}...">
                            @foreach($type->labels as $label)
                                <option value="{{ $label->label_id }}">{{ $label->name }}</option>
                            @endforeach
                        </x-tabler.form-select>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const typeSelector = document.getElementById('modal-type-selector');
                const navHierarchy = document.getElementById('modal-nav-hierarchy');
                const navTarget = document.getElementById('modal-nav-target');
                const parentIdSelector = document.getElementById('modal-parent-id-selector');

                function toggleTabs() {
                    if(!typeSelector) return;
                    const type = typeSelector.value;
                    if (type === 'performa') {
                        navHierarchy.style.display = 'block';
                        navTarget.style.display = 'none';
                        parentIdSelector.setAttribute('required', 'required');
                    } else {
                        navHierarchy.style.display = 'none';
                        navTarget.style.display = 'block';
                        parentIdSelector.removeAttribute('required');
                    }
                }

                if(typeSelector) {
                    typeSelector.addEventListener('change', toggleTabs);
                    toggleTabs();
                }

                const checkboxes = document.querySelectorAll('.modal-unit-checkbox');
                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        const unitId = this.dataset.id;
                        const targetInput = document.getElementById('modal-target-' + unitId);
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
    </x-tabler.form-modal>
@else
    @extends('layouts.admin.app')

    @section('header')
    <x-tabler.page-header title="Tambah Indikator Baru" pretitle="SPMI / Indikator">
        <x-slot:actions>
            <x-tabler.button href="javascript:history.back()" class="btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" />
        </x-slot:actions>
    </x-tabler.page-header>
    @endsection

    @section('content')
    <div class="container-xl">
        <ol class="breadcrumb" aria-label="breadcrumbs">
            <li class="breadcrumb-item"><a href="{{ route('pemutu.indikators.index') }}">Indikator</a></li>
            @if(isset($parentDok))
                <li class="breadcrumb-item"><a href="{{ route('pemutu.dokumens.show', $parentDok->dok_id) }}">{{ $parentDok->judul }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
        </ol>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <form action="{{ route('pemutu.indikators.store') }}" method="POST" class="ajax-form">
                @csrf
                
                <div class="card mb-3">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                            <li class="nav-item">
                                <a href="#tabs-info" class="nav-link active" data-bs-toggle="tab"><i class="ti ti-info-circle me-2"></i>Informasi Umum</a>
                            </li>
                            <li class="nav-item" id="nav-hierarchy" style="display: none;">
                                <a href="#tabs-hierarchy" class="nav-link" data-bs-toggle="tab"><i class="ti ti-hierarchy-2 me-2"></i>Struktur Hirarkis</a>
                            </li>
                            <li class="nav-item" id="nav-target">
                                <a href="#tabs-target" class="nav-link" data-bs-toggle="tab"><i class="ti ti-target me-2"></i>Target & Unit Kerja</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tabs-lainnya" class="nav-link" data-bs-toggle="tab"><i class="ti ti-tags me-2"></i>Label & Kategori</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- TAB 1: INFORMASI UMUM -->
                            <div class="tab-pane active show" id="tabs-info">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-tabler.form-select 
                                            id="type-selector"
                                            name="type" 
                                            label="Tipe Indikator" 
                                            :options="[
                                                'renop' => 'Indikator Renop',
                                                'standar' => 'Indikator Standar',
                                                'performa' => 'Indikator Performa (KPI)'
                                            ]"
                                            :selected="old('type', request('type'))" 
                                            required="true" 
                                            :readonly="isset($parentDok)"
                                        />
                                        @if(isset($parentDok))
                                            <input type="hidden" name="type" value="{{ request('type') }}">
                                            <div class="form-hint text-success small">Tipe dikunci karena menambah dari context dokumen.</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-tabler.form-input 
                                            name="no_indikator" 
                                            label="Kode / No Indikator" 
                                            type="text" 
                                            value="{{ old('no_indikator') }}"
                                            placeholder="cth: IND.01" 
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <x-tabler.form-select name="doksub_ids" label="Dokumen Penjaminan Mutu Terkait" required="true" type="select2" multiple="true" data-placeholder="Pilih satu atau lebih dokumen...">
                                            @foreach($dokumens as $doc)
                                                @if($doc->dokSubs->count() > 0)
                                                    <optgroup label="[{{ strtoupper($doc->jenis) }}] {{ $doc->judul }}">
                                                        @foreach($doc->dokSubs as $ds)
                                                            <option value="{{ $ds->doksub_id }}" {{ in_array($ds->doksub_id, $selectedDokSubs ?? []) ? 'selected' : '' }}>{{ $ds->judul }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        </x-tabler.form-select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <x-tabler.form-textarea name="indikator" label="Nama Indikator" rows="3" placeholder="Masukkan nama indikator..." required="true" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <x-tabler.form-textarea type="editor" id="keterangan" name="keterangan" label="Definisi / Keterangan" height="300" />
                                    </div>
                                </div>
                            </div>

                            <!-- TAB: HIRARKI -->
                            <div class="tab-pane" id="tabs-hierarchy">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <x-tabler.form-select name="parent_id" id="parent-id-selector" label="Indikator Induk" required="true" type="select2">
                                            <option value="">-- Pilih Indikator Standar --</option>
                                            @foreach($parents as $p)
                                                <option value="{{ $p->indikator_id }}">[{{ $p->no_indikator }}] {{ \Str::limit($p->indikator, 150) }}</option>
                                            @endforeach
                                        </x-tabler.form-select>
                                        <div class="form-hint">Indikator Performa HARUS merujuk pada satu Indikator Standar.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: TARGET & UNIT -->
                            <div class="tab-pane" id="tabs-target">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
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
                                                            function renderUnitRow($unit, $level = 0) {
                                                                $padding = $level * 20;
                                                                $isBold = $level < 2 ? 'fw-bold' : '';
                                                                $bg = ''; 
                                                                
                                                                echo '<tr class="'.$bg.'">';
                                                                echo '<td>';
                                                                echo '<div style="padding-left: '.$padding.'px">';
                                                                echo '<label class="form-check form-check-inline mb-0">';
                                                                echo '<input class="form-check-input unit-checkbox" type="checkbox" name="assignments['.$unit->orgunit_id.'][selected]" value="1" data-id="'.$unit->orgunit_id.'">';
                                                                echo '<span class="form-check-label '.$isBold.'">'.$unit->name.'</span>';
                                                                echo '</label>';
                                                                echo '</div>';
                                                                echo '</td>';
                                                                echo '<td>';
                                                                echo '<input type="text" class="form-control form-control-sm" name="assignments['.$unit->orgunit_id.'][target]" id="target-'.$unit->orgunit_id.'" placeholder="Target..." disabled>';
                                                                echo '</td>';
                                                                echo '</tr>';

                                                                if ($unit->children && $unit->children->count()) {
                                                                    foreach($unit->children as $child) {
                                                                        renderUnitRow($child, $level + 1);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    @foreach($orgUnits as $rootUnit)
                                                        {{ renderUnitRow($rootUnit) }}
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 3: LABEL & LAINNYA -->
                            <div class="tab-pane" id="tabs-lainnya">
                                <div class="row">
                                    @foreach($labelTypes as $type)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ $type->name }}</label>
                                        <x-tabler.form-select name="labels" id="label-{{ $type->labeltype_id }}" type="select2" multiple="true" data-placeholder="Pilih {{ $type->name }}...">
                                            @foreach($type->labels as $label)
                                                <option value="{{ $label->label_id }}">{{ $label->name }}</option>
                                            @endforeach
                                        </x-tabler.form-select>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy" text="Simpan Indikator" />
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelector = document.getElementById('type-selector');
            const navHierarchy = document.getElementById('nav-hierarchy');
            const navTarget = document.getElementById('nav-target');
            const parentIdSelector = document.getElementById('parent-id-selector');

            function toggleTabs() {
                if(!typeSelector) return;
                const type = typeSelector.value;
                if (type === 'performa') {
                    navHierarchy.style.display = 'block';
                    navTarget.style.display = 'none';
                    parentIdSelector.setAttribute('required', 'required');
                } else {
                    navHierarchy.style.display = 'none';
                    navTarget.style.display = 'block';
                    parentIdSelector.removeAttribute('required');
                }
            }

            if(typeSelector) {
                typeSelector.addEventListener('change', toggleTabs);
                toggleTabs();
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
@endif
