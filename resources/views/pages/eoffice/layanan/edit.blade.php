@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Edit Pengajuan: {{ $layanan->no_layanan }}" pretitle="{{ $layanan->jenisLayanan->nama_layanan }}">
    <x-slot:actions>
        <div class="btn-list">
            <x-tabler.button href="{{ route('eoffice.layanan.show', $layanan->hashid) }}" class="btn-link link-secondary" icon="ti ti-arrow-left" text="Kembali" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Revisi Data</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('eoffice.layanan.update', $layanan->hashid) }}" method="POST" class="ajax-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    @foreach($layanan->jenisLayanan->isians->sortBy('seq') as $isian)
                        @php
                            $field = $isian->kategoriIsian;
                            $fieldName = 'field_' . $field->kategoriisian_id;
                            $oldValue = $answers[$field->nama_isian]->isi ?? '';
                        @endphp

                        <div class="mb-3">
                            <label class="form-label {{ $isian->is_required ? 'required' : '' }}">
                                {{ $field->nama_isian }}
                            </label>

                            @if($field->type === 'textarea')
                                <textarea name="{{ $fieldName }}" class="form-control" rows="3" {{ $isian->is_required ? 'required' : '' }}>{{ $oldValue }}</textarea>
                            
                            @elseif($field->type === 'date')
                                <input type="date" name="{{ $fieldName }}" class="form-control" value="{{ $oldValue }}" {{ $isian->is_required ? 'required' : '' }}>
                            
                            @elseif($field->type === 'time')
                                <input type="time" name="{{ $fieldName }}" class="form-control" value="{{ $oldValue }}" {{ $isian->is_required ? 'required' : '' }}>
                            
                            @elseif($field->type === 'file')
                                @if($oldValue)
                                    <div class="mb-2">
                                        <span class="badge bg-blue-lt">File Saat Ini:</span> 
                                        <a href="{{ Storage::url($oldValue) }}" target="_blank" class="ms-1">{{ basename($oldValue) }}</a>
                                    </div>
                                @endif
                                <input type="file" name="{{ $fieldName }}" class="form-control">
                                <small class="form-hint">Upload file baru untuk mengganti yang lama.</small>
                            
                            @elseif($field->type === 'select')
                                @php
                                    $options = $field->type_value ?? []; // Assuming options are stored here if any
                                    // Fallback text input if simple select not implemented yet
                                @endphp
                                <select name="{{ $fieldName }}" class="form-select" {{ $isian->is_required ? 'required' : '' }}>
                                    <option value="">Pilih...</option>
                                    {{-- Custom options logic if needed --}}
                                </select>
                            
                            @else
                                <input type="text" name="{{ $fieldName }}" class="form-control" value="{{ $oldValue }}" {{ $isian->is_required ? 'required' : '' }}>
                            @endif

                            @if($isian->info_tambahan)
                                <small class="form-hint">{{ $isian->info_tambahan }}</small>
                            @endif
                        </div>
                    @endforeach

                    <div class="mb-3">
                        <x-tabler.form-textarea name="keterangan" label="Keterangan / Catatan Revisi" rows="3" value="{{ $layanan->keterangan }}" />
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-warning-lt">
                        <div class="card-body">
                            <h4 class="card-title text-warning">Status: {{ $layanan->latestStatus->status_layanan }}</h4>
                            <p>Data pengajuan ini dikembalikan oleh petugas untuk diperbaiki.</p>
                            <hr>
                            <strong>Catatan Petugas:</strong>
                            <p class="mb-0">{{ $layanan->latestStatus->keterangan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan & Ajukan Ulang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('form-success', function(e) {
   // Redirect handled by JSON response
});
</script>
@endpush
