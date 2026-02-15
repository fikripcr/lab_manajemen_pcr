@extends('layouts.admin.app')
@section('title', 'Edit Sasaran Kinerja')

@section('header')
<x-tabler.page-header title="Edit Sasaran Kinerja" pretitle="KPI">
    <x-slot:actions>
        <x-tabler.button type="a" href="{{ route('pemutu.kpi.index') }}" icon="ti ti-arrow-left" text="Kembali" class="btn-secondary" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{ route('pemutu.kpi.update', $indikator->indikator_id) }}" method="POST" class="card ajax-form">
            @csrf
            @method('PUT')
            
            <div class="card-header">
                <h3 class="card-title">Form Edit Sasaran Kinerja</h3>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <x-tabler.form-select 
                            name="parent_id" 
                            label="Indikator Standar (Induk)" 
                            type="select2" 
                            :options="$parents->mapWithKeys(function($p) {
                                return '[' . $p->no_indikator . '] ' . Str::limit($p->indikator, 150);
                            })->toArray()"
                            :selected="old('parent_id', $indikator->parent_id)" 
                            placeholder="Cari indikator standar..." 
                            required="true" 
                        />
                        <div class="form-hint">Pilih Indikator Standar yang menjadi acuan untuk sasaran kinerja ini.</div>
                    </div>

                    <div class="col-md-4 mb-3">
                         <x-tabler.form-input 
                            name="no_indikator" 
                            label="Kode / No. Sasaran" 
                            type="text" 
                            value="{{ old('no_indikator', $indikator->no_indikator) }}"
                            placeholder="cth: KPI.01" 
                        />
                    </div>
                
                    <div class="col-md-12 mb-3">
                        <x-tabler.form-textarea 
                            name="indikator" 
                            label="Nama Sasaran Kinerja" 
                            value="{{ old('indikator', $indikator->indikator) }}"
                            rows="3" 
                            required="true" 
                        />
                    </div>

                    <div class="col-md-12 mb-3">
                        <x-tabler.form-textarea 
                            name="keterangan" 
                            label="Definisi / Keterangan" 
                            value="{{ old('keterangan', $indikator->keterangan) }}"
                            rows="3" 
                            class="rich-text-editor" 
                        />
                    </div>
                </div>

                <div class="hr-text">Penugasan Personel</div>

                <div class="alert alert-info">
                     <i class="ti ti-info-circle me-2"></i> Pilih personel yang akan dievaluasi kinerjanya berdasarkan sasaran ini.
                </div>

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
                                @php
                                    $assigned = $indikator->personils->where('personil_id', $person->personil_id)->first();
                                    $isChecked = $assigned ? true : false;
                                @endphp
                            <tr>
                                <td>
                                    <label class="form-check mb-0">
                                        <input class="form-check-input kpi-checkbox" type="checkbox" name="kpi_assign[{{ $index }}][selected]" value="1" data-index="{{ $index }}" {{ $isChecked ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ $person->nama }}</span>
                                        <input type="hidden" name="kpi_assign[{{ $index }}][personil_id]" value="{{ $person->personil_id }}">
                                    </label>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="kpi_assign[{{ $index }}][year]" value="{{ $assigned ? $assigned->year : date('Y') }}" {{ !$isChecked ? 'disabled' : '' }} id="kpi-year-{{ $index }}">
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="kpi_assign[{{ $index }}][semester]" {{ !$isChecked ? 'disabled' : '' }} id="kpi-sem-{{ $index }}">
                                        <option value="Ganjil" {{ ($assigned && $assigned->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                        <option value="Genap" {{ ($assigned && $assigned->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="kpi_assign[{{ $index }}][weight]" placeholder="0.00" value="{{ $assigned ? $assigned->weight : '' }}" {{ !$isChecked ? 'disabled' : '' }} id="kpi-weight-{{ $index }}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="kpi_assign[{{ $index }}][target_value]" placeholder="0.00" value="{{ $assigned ? $assigned->target_value : '' }}" {{ !$isChecked ? 'disabled' : '' }} id="kpi-target-{{ $index }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

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
                height: 200,
                menubar: false,
                plugins: 'lists link table image code',
                toolbar: 'undo redo | blocks | bold italic | bullist numlist | link | code'
            });
        }

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
