@extends('layouts.admin.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">SPMI / Indikator</div>
                <h2 class="page-title">Edit Indikator</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('pemutu.indikators.show', $indikator->indikator_id) }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('pemutu.indikators.update', $indikator->indikator_id) }}" method="POST" class="card ajax-form">
            @csrf
            @method('PUT')
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tabs-info" class="nav-link active" data-bs-toggle="tab"><i class="ti ti-info-circle me-2"></i>Informasi Umum</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-target" class="nav-link" data-bs-toggle="tab"><i class="ti ti-target me-2"></i>Target & Unit</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-lainnya" class="nav-link" data-bs-toggle="tab"><i class="ti ti-tags me-2"></i>Label & Lainnya</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- TAB 1: INFORMASI UMUM -->
                    <div class="tab-pane active show" id="tabs-info">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Dokumen Rencana Operasional</label>
                                <select class="form-select select2" name="doksub_id" required>
                                    @foreach($dokumens as $dok)
                                        <optgroup label="{{ $dok->judul }}">
                                            @foreach($dok->dokSubs as $sub)
                                                <option value="{{ $sub->doksub_id }}" {{ $sub->doksub_id == $indikator->doksub_id ? 'selected' : '' }}>
                                                    {{ $sub->judul }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Nama Indikator</label>
                                <textarea name="indikator" class="form-control" rows="3" required>{{ $indikator->indikator }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Definisi / Keterangan</label>
                                <textarea name="keterangan" class="form-control rich-text-editor">{{ $indikator->keterangan }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: TARGET & UNIT -->
                    <div class="tab-pane" id="tabs-target">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Unit Penanggung Jawab & Target</label>
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
                                                $assignedMap = $indikator->orgUnits->keyBy('orgunit_id');

                                                function renderUnitRow($unit, $level = 0, $assignedMap) {
                                                    $padding = $level * 20;
                                                    $isBold = $level < 2 ? 'fw-bold' : '';
                                                    $bg = $level == 0 ? 'bg-light' : '';
                                                    
                                                    $isChecked = $assignedMap->has($unit->orgunit_id);
                                                    $targetVal = $isChecked ? $assignedMap->get($unit->orgunit_id)->pivot->target : '';
                                                    $isDisabled = !$isChecked ? 'disabled' : '';

                                                    echo '<tr class="'.$bg.'">';
                                                    echo '<td>';
                                                    echo '<div style="padding-left: '.$padding.'px">';
                                                    echo '<label class="form-check form-check-inline mb-0">';
                                                    echo '<input class="form-check-input unit-checkbox" type="checkbox" name="assignments['.$unit->orgunit_id.'][selected]" value="1" data-id="'.$unit->orgunit_id.'" '.($isChecked ? 'checked' : '').'>';
                                                    echo '<span class="form-check-label '.$isBold.'">'.$unit->name.'</span>';
                                                    echo '</label>';
                                                    echo '</div>';
                                                    echo '</td>';
                                                    echo '<td>';
                                                    echo '<input type="text" class="form-control form-control-sm" name="assignments['.$unit->orgunit_id.'][target]" id="target-'.$unit->orgunit_id.'" placeholder="Target..." value="'.$targetVal.'" '.$isDisabled.'>';
                                                    echo '</td>';
                                                    echo '</tr>';

                                                    if ($unit->children && $unit->children->count()) {
                                                        foreach($unit->children as $child) {
                                                            renderUnitRow($child, $level + 1, $assignedMap);
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
                                <div class="form-hint">Centang unit yang relevan dan isi targetnya.</div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: LABEL & LAINNYA -->
                    <div class="tab-pane" id="tabs-lainnya">
                            <div class="row">

                            @foreach($labelTypes as $type)
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ $type->name }}</label>
                                <select class="form-select select2" name="labels[]" multiple data-placeholder="Pilih {{ $type->name }}...">
                                    @php 
                                        // Get selected labels for this type
                                        $selectedLabelIds = $indikator->labels->where('type_id', $type->labeltype_id)->pluck('label_id')->toArray();
                                    @endphp
                                    @foreach($type->labels as $label)
                                        <option value="{{ $label->label_id }}" {{ in_array($label->label_id, $selectedLabelIds) ? 'selected' : '' }}>
                                            {{ $label->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i> Update Indikator
                </button>
            </div>
        </form>
    </div>
</div>

@push('css')
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                }
            });
        });
    });
</script>
@endpush
@endsection
