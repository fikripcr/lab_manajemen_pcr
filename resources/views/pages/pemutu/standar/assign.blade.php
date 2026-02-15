@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Penugasan Indikator Standar" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.standar.index') }}" style="secondary" icon="ti ti-arrow-left">
            Kembali
        </x-tabler.button>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="mb-3">
    <div class="card card-stacked">
        <div class="card-body">
            <div class="fw-bold">Indikator:</div>
            <div class="h3">{{ $indikator->indikator }}</div>
            <div class="text-muted small">Tipe: {{ ucfirst($indikator->type) }}</div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-md-5">
        <form method="POST" action="{{ route('pemutu.standar.storeAssignment', $indikator->indikator_id) }}" class="card ajax-form">
            @csrf
            <div class="card-header">
                <h3 class="card-title">Tugaskan ke Personel</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <x-tabler.form-select name="personil_id" label="Personel" required="true">
                        <option value="">Pilih Personel...</option>
                        @foreach($personils as $personil)
                            <option value="{{ $personil->personil_id }}">{{ $personil->nama }} ({{ $personil->jenis }})</option>
                        @endforeach
                    </x-tabler.form-select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-tabler.form-input name="year" label="Tahun" type="number" value="{{ date('Y') }}" required="true" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-tabler.form-select name="semester" label="Semester" required="true">
                            <option value="1">Ganjil</option>
                            <option value="2">Genap</option>
                        </x-tabler.form-select>
                    </div>
                </div>
                <div class="mb-3">
                    <x-tabler.form-input name="target_value" label="Target (Opsional Override)" placeholder="{{ $indikator->target }}" />
                    <small class="form-hint">Kosongkan untuk menggunakan target default: {{ $indikator->target }}</small>
                </div>
                <div class="mb-3">
                    <x-tabler.form-input name="weight" label="Bobot (%)" type="number" step="0.01" value="0" />
                </div>
            </div>
            <div class="card-footer text-end">
                <x-tabler.button type="submit" style="primary">
                    Tugaskan
                </x-tabler.button>
            </div>
        </form>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Penugasan Saat Ini</h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap">
                    <thead>
                        <tr>
                            <th>Personel</th>
                            <th>Periode</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assigned as $assign)
                        <tr>
                            <td>
                                <div>{{ $assign->personil->nama ?? 'Unknown' }}</div>
                                <div class="text-muted small">{{ $assign->personil->email ?? '' }}</div>
                            </td>
                            <td>{{ $assign->year }} / {{ $assign->semester == 1 ? 'Ganjil' : 'Genap' }}</td>
                            <td>{{ $assign->target_value ?? $indikator->target }}</td>
                            <td>
                                <span class="badge bg-{{ $assign->status == 'approved' ? 'green' : ($assign->status == 'submitted' ? 'blue' : 'secondary') }}">
                                    {{ ucfirst($assign->status) }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete" 
                                    data-url="{{ route('pemutu.standar.destroyAssignment', $assign->id) }}" 
                                    data-title="Hapus Penugasan?" 
                                    data-text="Data penugasan akan dihapus.">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada penugasan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@endsection
