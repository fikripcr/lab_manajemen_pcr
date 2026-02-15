@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Update Realisasi KPI" pretitle="My KPI">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.mykpi.index') }}" style="secondary" icon="ti ti-arrow-left">
            Kembali
        </x-tabler.button>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <form method="POST" action="{{ route('pemutu.mykpi.update', $kpi->id) }}" enctype="multipart/form-data" class="card ajax-form">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label text-muted">Indikator</label>
                    <div class="h3">{{ $kpi->indikator->indikator }}</div>
                    <div class="text-muted small">Target: {{ $kpi->target_value ?? $kpi->indikator->target }}</div>
                </div>

                <div class="mb-3">
                    <x-tabler.form-textarea 
                        name="realization" 
                        label="Realisasi / Capaian" 
                        rows="4" 
                        placeholder="Deskripsikan pencapaian Anda..." 
                        value="{{ old('realization', $kpi->realization) }}"
                        required="true"
                    />
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-tabler.form-input 
                            name="score" 
                            label="Skor (0-100)" 
                            type="number" 
                            min="0" 
                            max="100" 
                            step="0.01" 
                            value="{{ old('score', $kpi->score) }}" 
                        />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-tabler.form-input 
                            name="attachment" 
                            label="Bukti Dukung (Opsional)" 
                            type="file" 
                        />
                        @if($kpi->attachment)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $kpi->attachment) }}" target="_blank" class="btn btn-sm btn-ghost-info">
                                    <i class="ti ti-download me-1"></i> Lihat Lampiran Saat Ini
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <x-tabler.button type="submit" style="primary">
                    Simpan & Ajukan
                </x-tabler.button>
            </div>
        </form>
    </div>
</div>
@endsection
