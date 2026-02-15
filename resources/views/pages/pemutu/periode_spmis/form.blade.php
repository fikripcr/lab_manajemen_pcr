@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="Periode SPMI">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.periode-spmis.index') }}" style="secondary" icon="ti ti-arrow-left">
            Kembali
        </x-tabler.button>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<form action="{{ isset($periodeSpmi) ? route('pemutu.periode-spmis.update', $periodeSpmi) : route('pemutu.periode-spmis.store') }}" method="POST" class="ajax-form">
    @csrf
    @if(isset($periodeSpmi))
        @method('PUT')
    @endif

    <div class="row row-cards">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Informasi Dasar</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <x-tabler.form-input 
                            name="periode" 
                            label="Tahun Periode" 
                            type="number" 
                            value="{{ old('periode', $periodeSpmi->periode ?? date('Y')) }}" 
                            required="true" 
                        />
                    </div>
                    <div class="mb-3">
                        <x-tabler.form-select 
                            name="jenis_periode" 
                            label="Jenis Periode" 
                            required="true"
                            :options="[
                                'Tahunan' => 'Tahunan',
                                'Semester Ganjil' => 'Semester Ganjil',
                                'Semester Genap' => 'Semester Genap'
                            ]"
                            :selected="old('jenis_periode', $periodeSpmi->jenis_periode ?? '')"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Siklus PPEPP</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Penetapan -->
                        <div class="col-12 border-bottom pb-3">
                            <label class="form-label fw-bold text-primary"><i class="ti ti-checkbox me-1"></i> 1. Penetapan</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="penetapan_awal" 
                                        label="Tgl Awal" 
                                        type="date" 
                                        value="{{ old('penetapan_awal', isset($periodeSpmi) && $periodeSpmi->penetapan_awal ? $periodeSpmi->penetapan_awal->format('Y-m-d') : '') }}" 
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="penetapan_akhir" 
                                        label="Tgl Akhir" 
                                        type="date" 
                                        value="{{ old('penetapan_akhir', isset($periodeSpmi) && $periodeSpmi->penetapan_akhir ? $periodeSpmi->penetapan_akhir->format('Y-m-d') : '') }}" 
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Pelaksanaan / ED -->
                        <div class="col-12 border-bottom pb-3">
                            <label class="form-label fw-bold text-success"><i class="ti ti-player-play me-1"></i> 2. Pelaksanaan / Evaluasi Diri (ED)</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="ed_awal" 
                                        label="Tgl Awal" 
                                        type="date" 
                                        value="{{ old('ed_awal', isset($periodeSpmi) && $periodeSpmi->ed_awal ? $periodeSpmi->ed_awal->format('Y-m-d') : '') }}" 
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="ed_akhir" 
                                        label="Tgl Akhir" 
                                        type="date" 
                                        value="{{ old('ed_akhir', isset($periodeSpmi) && $periodeSpmi->ed_akhir ? $periodeSpmi->ed_akhir->format('Y-m-d') : '') }}" 
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Evaluasi / AMI -->
                        <div class="col-12 border-bottom pb-3">
                            <label class="form-label fw-bold text-info"><i class="ti ti-search me-1"></i> 3. Evaluasi / AMI</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="ami_awal" 
                                        label="Tgl Awal" 
                                        type="date" 
                                        value="{{ old('ami_awal', isset($periodeSpmi) && $periodeSpmi->ami_awal ? $periodeSpmi->ami_awal->format('Y-m-d') : '') }}" 
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="ami_akhir" 
                                        label="Tgl Akhir" 
                                        type="date" 
                                        value="{{ old('ami_akhir', isset($periodeSpmi) && $periodeSpmi->ami_akhir ? $periodeSpmi->ami_akhir->format('Y-m-d') : '') }}" 
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Pengendalian -->
                        <div class="col-12 border-bottom pb-3">
                            <label class="form-label fw-bold text-warning"><i class="ti ti-shield-check me-1"></i> 4. Pengendalian</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="pengendalian_awal" 
                                        label="Tgl Awal" 
                                        type="date" 
                                        value="{{ old('pengendalian_awal', isset($periodeSpmi) && $periodeSpmi->pengendalian_awal ? $periodeSpmi->pengendalian_awal->format('Y-m-d') : '') }}" 
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="pengendalian_akhir" 
                                        label="Tgl Akhir" 
                                        type="date" 
                                        value="{{ old('pengendalian_akhir', isset($periodeSpmi) && $periodeSpmi->pengendalian_akhir ? $periodeSpmi->pengendalian_akhir->format('Y-m-d') : '') }}" 
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Peningkatan -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-danger"><i class="ti ti-trending-up me-1"></i> 5. Peningkatan</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="peningkatan_awal" 
                                        label="Tgl Awal" 
                                        type="date" 
                                        value="{{ old('peningkatan_awal', isset($periodeSpmi) && $periodeSpmi->peningkatan_awal ? $periodeSpmi->peningkatan_awal->format('Y-m-d') : '') }}" 
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="peningkatan_akhir" 
                                        label="Tgl Akhir" 
                                        type="date" 
                                        value="{{ old('peningkatan_akhir', isset($periodeSpmi) && $periodeSpmi->peningkatan_akhir ? $periodeSpmi->peningkatan_akhir->format('Y-m-d') : '') }}" 
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <x-tabler.button type="submit" style="primary">
                        Simpan Periode
                    </x-tabler.button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

