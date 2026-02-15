@extends('layouts.admin.app')
@section('title', 'Penugasan Personel')

@section('header')
<x-tabler.page-header title="Penugasan Personel" pretitle="KPI">
    <x-slot:actions>
        <x-tabler.button type="a" href="{{ route('pemutu.kpi.index') }}" icon="ti ti-arrow-left" text="Kembali" class="btn-secondary" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Indikator Performa</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-muted">Kode</div>
                    <div class="col-md-10 fw-bold">{{ $indikator->no_indikator }}</div>
                    <div class="col-md-2 text-muted">Indikator</div>
                    <div class="col-md-10 fs-3">{{ $indikator->indikator }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter & Pencarian</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Cari Nama / Email</label>
                    <input type="text" id="member-search" class="form-control" placeholder="Ketik nama...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Unit Kerja</label>
                    <select id="unit-filter" class="form-select">
                        <option value="">Semua Unit</option>
                        @foreach($orgUnits as $unit)
                            <option value="{{ $unit->orgunit_id }}">{{ $unit->name }}</option>
                            @foreach($unit->children as $child)
                                <option value="{{ $child->orgunit_id }}">-- {{ $child->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="hr-text">Bulk Action</div>
                <div class="mb-2">
                    <label class="form-label small">Bobot (%)</label>
                    <input type="number" step="0.01" id="bulk-weight" class="form-control form-control-sm" placeholder="0.00">
                </div>
                <div class="mb-3">
                    <label class="form-label small">Target</label>
                    <input type="number" step="0.01" id="bulk-target" class="form-control form-control-sm" placeholder="0.00">
                </div>
                <button type="button" id="btn-apply-bulk" class="btn btn-outline-primary btn-sm w-100">
                    Terapkan ke Terpilih
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <form action="{{ route('pemutu.kpi.assign.store', $indikator->indikator_id) }}" method="POST" class="ajax-form">
            @csrf
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Daftar Personel <span id="personnel-count" class="badge bg-blue-lt ms-2">{{ $personils->count() }}</span></h3>
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="check-all-filtered">
                        <label class="form-check-label">Pilih Semua yang Terfilter</label>
                    </div>
                </div>
                <div class="table-responsive border-bottom" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-vcenter table-mobile-md card-table table-striped" id="personnel-table">
                        <thead>
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" class="form-check-input" id="master-checkbox">
                                </th>
                                <th width="35%">Pegawai</th>
                                <th width="15%">Unit Kerja</th>
                                <th width="15%">Bobot (%)</th>
                                <th width="15%">Target</th>
                                <th width="15%">Tahun/Sem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personils as $index => $person)
                            @php
                                $isAssigned = in_array($person->personil_id, $assignedPersonilIds);
                                $assignData = $assignments->get($person->personil_id);
                            @endphp
                            <tr class="person-row" 
                                data-name="{{ strtolower($person->nama) }}" 
                                data-unit="{{ $person->org_unit_id }}"
                                data-parent-unit="{{ $person->orgUnit->parent_id ?? '' }}">
                                <td>
                                    <input type="checkbox" class="form-check-input kpi-checkbox" 
                                        name="kpi_assign[{{ $index }}][selected]" 
                                        value="1" 
                                        data-index="{{ $index }}"
                                        {{ $isAssigned ? 'checked' : '' }}>
                                    <input type="hidden" name="kpi_assign[{{ $index }}][personil_id]" value="{{ $person->personil_id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm me-2" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($person->nama) }})"></span>
                                        <div class="flex-fill">
                                            <div class="font-weight-medium">{{ $person->nama }}</div>
                                            <div class="text-muted small">{{ $person->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="small">{{ $person->orgUnit->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="kpi_assign[{{ $index }}][weight]" 
                                        class="form-control form-control-sm weight-input" 
                                        value="{{ $assignData ? $assignData->weight : '' }}"
                                        {{ $isAssigned ? '' : 'disabled' }}
                                        id="weight-{{ $index }}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="kpi_assign[{{ $index }}][target_value]" 
                                        class="form-control form-control-sm target-input" 
                                        value="{{ $assignData ? $assignData->target_value : '' }}"
                                        {{ $isAssigned ? '' : 'disabled' }}
                                        id="target-{{ $index }}">
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <input type="number" name="kpi_assign[{{ $index }}][year]" 
                                            class="form-control form-control-sm py-0 h-auto" style="width: 60px"
                                            value="{{ $assignData ? $assignData->year : ($activePeriode->year ?? date('Y')) }}"
                                            {{ $isAssigned ? '' : 'disabled' }}
                                            id="year-{{ $index }}">
                                        
                                        <select name="kpi_assign[{{ $index }}][semester]" 
                                            class="form-select form-select-sm py-0 h-auto"
                                            {{ $isAssigned ? '' : 'disabled' }}
                                            id="sem-{{ $index }}">
                                            <option value="Ganjil" {{ ($assignData ? $assignData->semester : ($activePeriode->semester ?? '')) == 'Ganjil' ? 'selected' : '' }}>G</option>
                                            <option value="Genap" {{ ($assignData ? $assignData->semester : ($activePeriode->semester ?? '')) == 'Genap' ? 'selected' : '' }}>E</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="kpi_assign[{{ $index }}][periode_kpi_id]" value="{{ $activePeriode->periode_kpi_id ?? '' }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-2"></i> Simpan Penugasan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('member-search');
        const unitFilter = document.getElementById('unit-filter');
        const rows = document.querySelectorAll('.person-row');
        const countBadge = document.getElementById('personnel-count');
        const masterCheckbox = document.getElementById('master-checkbox');
        const checkAllFiltered = document.getElementById('check-all-filtered');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const unitId = unitFilter.value;
            let count = 0;

            rows.forEach(row => {
                const name = row.dataset.name;
                const rowUnit = row.dataset.unit;
                const parentUnit = row.dataset.parentUnit;

                const matchesSearch = name.includes(searchTerm);
                const matchesUnit = !unitId || rowUnit === unitId || parentUnit === unitId;

                if (matchesSearch && matchesUnit) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });
            countBadge.textContent = count;
        }

        searchInput.addEventListener('input', filterTable);
        unitFilter.addEventListener('change', filterTable);

        // Checkbox enable/disable logic
        const checkboxes = document.querySelectorAll('.kpi-checkbox');
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const index = this.dataset.index;
                const inputs = [
                    document.getElementById('weight-' + index),
                    document.getElementById('target-' + index),
                    document.getElementById('year-' + index),
                    document.getElementById('sem-' + index)
                ];
                
                inputs.forEach(input => {
                    if (this.checked) {
                        input.removeAttribute('disabled');
                    } else {
                        input.setAttribute('disabled', 'disabled');
                    }
                });
            });
        });

        // Bulk apply logic
        document.getElementById('btn-apply-bulk').addEventListener('click', function() {
            const weight = document.getElementById('bulk-weight').value;
            const target = document.getElementById('bulk-target').value;
            
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cb = row.querySelector('.kpi-checkbox');
                    if (cb.checked) {
                        const index = cb.dataset.index;
                        if (weight) document.getElementById('weight-' + index).value = weight;
                        if (target) document.getElementById('target-' + index).value = target;
                    }
                }
            });
        });

        // Master checkbox logic (Select All in current view - regardless of filter)
        masterCheckbox.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
                cb.dispatchEvent(new Event('change'));
            });
        });

        // Check all filtered logic
        checkAllFiltered.addEventListener('change', function() {
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cb = row.querySelector('.kpi-checkbox');
                    cb.checked = this.checked;
                    cb.dispatchEvent(new Event('change'));
                }
            });
        });
    });
</script>
@endpush
