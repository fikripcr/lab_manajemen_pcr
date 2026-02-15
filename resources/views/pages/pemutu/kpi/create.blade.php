@extends('layouts.admin.app')
@section('title', 'Tambah Sasaran Kinerja')

@section('header')
<x-tabler.page-header title="Tambah Sasaran Kinerja" pretitle="KPI">
    <x-slot:actions>
        <x-tabler.button type="a" href="{{ route('pemutu.kpi.index') }}" icon="ti ti-arrow-left" text="Kembali" class="btn-secondary" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{ route('pemutu.kpi.store') }}" method="POST" class="card ajax-form">
            @csrf
            <div class="card-header">
                <h3 class="card-title">Form Input Sasaran Kinerja</h3>
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
                            :selected="old('parent_id')" 
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
                            placeholder="cth: KPI.01" 
                            value="{{ old('no_indikator') }}"
                        />
                    </div>
                
                    <x-tabler.form-textarea name="indikator" label="Nama Sasaran Kinerja" rows="3" placeholder="Deskripsikan sasaran kinerja..." required="true" :value="old('indikator')" />

                    <x-tabler.form-textarea type="editor" name="keterangan" label="Definisi / Keterangan" :value="old('keterangan')" height="200" />
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
                            <tr>
                                <td>
                                    <x-tabler.form-checkbox 
                                        name="kpi_assign[{{ $index }}][selected]" 
                                        label="{{ $person->nama }}" 
                                        value="1" 
                                        input-class="kpi-checkbox" 
                                        class="mb-0" 
                                        data-index="{{ $index }}"
                                    >
                                        <input type="hidden" name="kpi_assign[{{ $index }}][personil_id]" value="{{ $person->personil_id }}">
                                    </x-tabler.form-checkbox>
                                </td>
                                <td>
                                    <x-tabler.form-input type="number" name="kpi_assign[{{ $index }}][year]" value="{{ date('Y') }}" disabled="true" id="kpi-year-{{ $index }}" class="mb-0" input-class="form-control-sm" />
                                </td>
                                <td>
                                    <x-tabler.form-select name="kpi_assign[{{ $index }}][semester]" :options="['Ganjil' => 'Ganjil', 'Genap' => 'Genap']" class="form-select-sm" disabled id="kpi-sem-{{ $index }}" />
                                </td>
                                <td>
                                    <x-tabler.form-input type="number" step="0.01" name="kpi_assign[{{ $index }}][weight]" placeholder="0.00" disabled="true" id="kpi-weight-{{ $index }}" class="mb-0" input-class="form-control-sm" />
                                </td>
                                <td>
                                    <x-tabler.form-input type="number" step="0.01" name="kpi_assign[{{ $index }}][target_value]" placeholder="0.00" disabled="true" id="kpi-target-{{ $index }}" class="mb-0" input-class="form-control-sm" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Simpan Sasaran Kinerja</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
