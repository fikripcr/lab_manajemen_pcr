@extends('layouts.admin.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ route('pemutu.periode-spmis.index') }}">Periode SPMI</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ isset($periodeSpmi) ? 'Edit' : 'Tambah' }}</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    {{ $pageTitle }}
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="{{ isset($periodeSpmi) ? route('pemutu.periode-spmis.update', $periodeSpmi) : route('pemutu.periode-spmis.store') }}" method="POST">
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
                                <label class="form-label required">Tahun Periode</label>
                                <input type="number" name="periode" class="form-control" value="{{ old('periode', $periodeSpmi->periode ?? date('Y')) }}" required>
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
                            <div class="row gap-y-3">
                                <!-- Penetapan -->
                                <div class="col-12 border-bottom pb-3 mb-3">
                                    <label class="form-label font-weight-bold text-primary"><i class="ti ti-checkbox me-1"></i> 1. Penetapan</label>
                                    <div class="row row-cards">
                                        <div class="col-md-6">
                                            <label class="form-label small">Tgl Awal</label>
                                            <input type="text" name="penetapan_awal" class="form-control datepicker" value="{{ old('penetapan_awal', isset($periodeSpmi) && $periodeSpmi->penetapan_awal ? $periodeSpmi->penetapan_awal->format('Y-m-d') : '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Tgl Akhir</label>
                                            <input type="text" name="penetapan_akhir" class="form-control datepicker" value="{{ old('penetapan_akhir', isset($periodeSpmi) && $periodeSpmi->penetapan_akhir ? $periodeSpmi->penetapan_akhir->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Pelaksanaan / ED -->
                                <div class="col-12 border-bottom pb-3 mb-3">
                                    <label class="form-label font-weight-bold text-success"><i class="ti ti-player-play me-1"></i> 2. Pelaksanaan / Evaluasi Diri (ED)</label>
                                    <div class="row row-cards">
                                        <div class="col-md-6">
                                            <label class="form-label small">Tgl Awal</label>
                                            <input type="text" name="ed_awal" class="form-control datepicker" value="{{ old('ed_awal', isset($periodeSpmi) && $periodeSpmi->ed_awal ? $periodeSpmi->ed_awal->format('Y-m-d') : '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Tgl Akhir</label>
                                            <input type="text" name="ed_akhir" class="form-control datepicker" value="{{ old('ed_akhir', isset($periodeSpmi) && $periodeSpmi->ed_akhir ? $periodeSpmi->ed_akhir->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Evaluasi / AMI -->
                                <div class="col-12 border-bottom pb-3 mb-3">
                                    <label class="form-label font-weight-bold text-info"><i class="ti ti-search me-1"></i> 3. Evaluasi / AMI</label>
                                    <div class="row row-cards">
                                        <div class="col-md-6">
                                            <label class="form-label small">Tgl Awal</label>
                                            <input type="text" name="ami_awal" class="form-control datepicker" value="{{ old('ami_awal', isset($periodeSpmi) && $periodeSpmi->ami_awal ? $periodeSpmi->ami_awal->format('Y-m-d') : '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Tgl Akhir</label>
                                            <input type="text" name="ami_akhir" class="form-control datepicker" value="{{ old('ami_akhir', isset($periodeSpmi) && $periodeSpmi->ami_akhir ? $periodeSpmi->ami_akhir->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Pengendalian -->
                                <div class="col-12 border-bottom pb-3 mb-3">
                                    <label class="form-label font-weight-bold text-warning"><i class="ti ti-shield-check me-1"></i> 4. Pengendalian</label>
                                    <div class="row row-cards">
                                        <div class="col-md-6">
                                            <label class="form-label small">Tgl Awal</label>
                                            <input type="text" name="pengendalian_awal" class="form-control datepicker" value="{{ old('pengendalian_awal', isset($periodeSpmi) && $periodeSpmi->pengendalian_awal ? $periodeSpmi->pengendalian_awal->format('Y-m-d') : '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Tgl Akhir</label>
                                            <input type="text" name="pengendalian_akhir" class="form-control datepicker" value="{{ old('pengendalian_akhir', isset($periodeSpmi) && $periodeSpmi->pengendalian_akhir ? $periodeSpmi->pengendalian_akhir->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Peningkatan -->
                                <div class="col-12">
                                    <label class="form-label font-weight-bold text-danger"><i class="ti ti-trending-up me-1"></i> 5. Peningkatan</label>
                                    <div class="row row-cards">
                                        <div class="col-md-6">
                                            <label class="form-label small">Tgl Awal</label>
                                            <input type="text" name="peningkatan_awal" class="form-control datepicker" value="{{ old('peningkatan_awal', isset($periodeSpmi) && $periodeSpmi->peningkatan_awal ? $periodeSpmi->peningkatan_awal->format('Y-m-d') : '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Tgl Akhir</label>
                                            <input type="text" name="peningkatan_akhir" class="form-control datepicker" value="{{ old('peningkatan_akhir', isset($periodeSpmi) && $periodeSpmi->peningkatan_akhir ? $periodeSpmi->peningkatan_akhir->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('pemutu.periode-spmis.index') }}" class="btn btn-link">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if(window.flatpickr) {
            flatpickr('.datepicker', {
                dateFormat: 'Y-m-d',
                allowInput: true
            });
        }
    });
</script>
@endsection
