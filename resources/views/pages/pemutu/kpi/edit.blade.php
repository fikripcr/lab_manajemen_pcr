@extends('layouts.tabler.app')
@section('title', 'Edit Sasaran Kinerja')

@section('header')
<x-tabler.page-header title="Ubah Sasaran Kinerja" pretitle="KPI">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.kpi.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{ route('pemutu.kpi.update', $indikator->indikator_id) }}" method="POST" class="card ajax-form">
            @csrf
            <input type="hidden" name="type" value="performa">
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
                                return [$p->indikator_id => '[' . $p->no_indikator . '] ' . Str::limit($p->indikator, 150)];
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
                            type="editor"
                            height="150"
                        />
                    </div>
                </div>

            </div>

            <div class="card-footer text-end">
                <x-tabler.button type="submit" />
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // No additional scripts needed
    });
</script>
@endpush
