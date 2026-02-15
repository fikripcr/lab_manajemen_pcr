@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ route('pemutu.indikators.index') }}">Indikator</a></li>
                        @if(isset($parentDok))
                            <li class="breadcrumb-item"><a href="{{ route('pemutu.dokumens.show', $parentDok->dok_id) }}">{{ $parentDok->judul }}</a></li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
                    </ol>
                </div>
                <h2 class="page-title">Tambah Indikator Baru</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
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
                        <li class="nav-item" id="nav-kpi" style="display: none;">
                            <a href="#tabs-kpi" class="nav-link" data-bs-toggle="tab"><i class="ti ti-users me-2"></i>Sasaran Kinerja</a>
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
                                        name="type" 
                                        label="Tipe Indikator" 
                                        :options="[
                                            'renop' => 'Indikator Renop',
                                            'standar' => 'Indikator Standar',
                                            'performa' => 'Indikator Performa (KPI)'
                                        ]"
                                        :selected="old('type')" 
                                        required="true" 
                                        :readonly="isset($parentDok)"
                                        class="@if(isset($parentDok)) bg-light @endif"
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
                                <div class="col-md-12 mb-3">
                                    <x-tabler.form-select name="doksub_ids[]" label="Dokumen Penjaminan Mutu Terkait" required="true" class="select2" multiple="true" data-placeholder="Pilih satu atau lebih dokumen...">
                                        @foreach($dokumens as $doc)
                                            <optgroup label="[{{ strtoupper($doc->jenis) }}] {{ $doc->judul }}">
                                                @foreach($doc->dokSubs as $ds)
                                                    <option value="{{ $ds->doksub_id }}" {{ in_array($ds->doksub_id, $selectedDokSubs) ? 'selected' : '' }}>{{ $ds->judul }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </x-tabler.form-select>
                                    @if(isset($parentDok))
                                        <div class="form-hint text-success small">Dokumen terkait otomatis dipilih berdasarkan asal navigasi.</div>
                                    @endif
                                    <div class="form-hint">Pilih dokumen Renop, Standar, atau lainnya yang terkait dengan indikator ini.</div>
                                </div>
                                <x-tabler.form-textarea name="indikator" label="Nama Indikator" rows="3" placeholder="Masukkan nama indikator..." required="true" />
                                <x-tabler.form-textarea type="editor" name="keterangan" label="Definisi / Keterangan" height="300" />
                            </div>
                        </div>

                        <!-- TAB: HIRARKI -->
                        <div class="tab-pane" id="tabs-hierarchy">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <x-tabler.form-select name="parent_id" id="parent-id-selector" label="Indikator Induk" required="true" class="select2">
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
                                                            $bg = $level == 0 ? 'bg-light' : '';
                                                            
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
                                    <div class="form-hint">Centang unit yang relevan dan isi targetnya.</div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: KPI PEGAWAI -->
                        <div class="tab-pane" id="tabs-kpi">
                            <div class="table-responsive border rounded" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="30%">Pegawai</th>
                                            <th width="15%">Tahun</th>
                                            <th width="15%">Semester</th>
                                            <th width="15%">Bobot (%)</th>
                                            <th width="25%">Target Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($personils as $index => $person)
                                        <tr>
                                            <td>
                                                <label class="form-check mb-0">
                                                    <input class="form-check-input kpi-checkbox" type="checkbox" name="kpi_assign[{{ $index }}][selected]" value="1" data-index="{{ $index }}">
                                                    <span class="form-check-label">{{ $person->nama }}</span>
                                                    <input type="hidden" name="kpi_assign[{{ $index }}][personil_id]" value="{{ $person->personil_id }}">
                                                </label>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm" name="kpi_assign[{{ $index }}][year]" value="{{ date('Y') }}" disabled id="kpi-year-{{ $index }}">
                                            </td>
                                            <td>
                                                <x-tabler.form-select name="kpi_assign[{{ $index }}][semester]" :options="['Ganjil' => 'Ganjil', 'Genap' => 'Genap']" disabled="true" class="form-select-sm" id="kpi-sem-{{ $index }}" />
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" class="form-control form-control-sm" name="kpi_assign[{{ $index }}][weight]" placeholder="0.00" disabled id="kpi-weight-{{ $index }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" class="form-control form-control-sm" name="kpi_assign[{{ $index }}][target_value]" placeholder="0.00" disabled id="kpi-target-{{ $index }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-hint mt-2">Pilih pegawai yang akan dievaluasi menggunakan indikator performa ini.</div>
                        </div>

                        <!-- TAB 3: LABEL & LAINNYA -->
                        <div class="tab-pane" id="tabs-lainnya">
                            <div class="row">
                                
                                @foreach($labelTypes as $type)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ $type->name }}</label>
                                    <x-tabler.form-select name="labels[]" class="select2" multiple="true" data-placeholder="Pilih {{ $type->name }}...">
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
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i> Simpan Indikator
                </button>
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
        const navKpi = document.getElementById('nav-kpi');
        const parentIdSelector = document.getElementById('parent-id-selector');

        function toggleTabs() {
            const type = typeSelector.value;
            if (type === 'performa') {
                navHierarchy.style.display = 'block';
                navKpi.style.display = 'block';
                navTarget.style.display = 'none';
                parentIdSelector.setAttribute('required', 'required');
            } else {
                navHierarchy.style.display = 'none';
                navKpi.style.display = 'none';
                navTarget.style.display = 'block';
                parentIdSelector.removeAttribute('required');
            }
        }

        typeSelector.addEventListener('change', toggleTabs);
        toggleTabs(); // Initial state

        // Init Select2
        if (window.loadSelect2) {
            window.loadSelect2().then(() => {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });
            });
        }

        // Initialize HugeRTE
        if (window.loadHugeRTE) {
            window.loadHugeRTE('.rich-text-editor', {
                height: 300,
                menubar: false,
                plugins: 'lists link table image code',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image | table | code'
            });
        }

        // Checkbox Logic for Target Input
        const checkboxes = document.querySelectorAll('.unit-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const unitId = this.dataset.id;
                const targetInput = document.getElementById('target-' + unitId);
                if (this.checked) {
                    targetInput.removeAttribute('disabled');
                    targetInput.focus();
                } else {
                    targetInput.setAttribute('disabled', 'disabled');
                    targetInput.value = ''; // Clear value if unchecked? Optional.
                }
            });
        });

        // KPI Checkbox Logic
        const kpiCheckboxes = document.querySelectorAll('.kpi-checkbox');
        kpiCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const index = this.dataset.index;
                const yearInput = document.getElementById('kpi-year-' + index);
                const semInput = document.getElementById('kpi-sem-' + index);
                const weightInput = document.getElementById('kpi-weight-' + index);
                const targetInput = document.getElementById('kpi-target-' + index);
                
                if (this.checked) {
                    yearInput.removeAttribute('disabled');
                    semInput.removeAttribute('disabled');
                    weightInput.removeAttribute('disabled');
                    targetInput.removeAttribute('disabled');
                    weightInput.focus();
                } else {
                    yearInput.setAttribute('disabled', 'disabled');
                    semInput.setAttribute('disabled', 'disabled');
                    weightInput.setAttribute('disabled', 'disabled');
                    targetInput.setAttribute('disabled', 'disabled');
                }
            });
        });
    });
</script>
@endpush
@endsection
