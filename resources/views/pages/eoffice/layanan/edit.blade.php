@extends('layouts.tabler.app')

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
                            $required = $isian->is_required ? 'required' : '';
                        @endphp

                        <div class="mb-3">
                            @if($field->type === 'textarea')
                                <x-tabler.form-textarea name="{{ $fieldName }}" label="{{ $field->nama_isian }}" rows="3" required="{{ $isian->is_required }}">{{ $oldValue }}</x-tabler.form-textarea>
                            
                            @elseif($field->type === 'date')
                                <x-tabler.form-input type="date" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" value="{{ $oldValue }}" required="{{ $isian->is_required }}" />
                            
                            @elseif($field->type === 'time')
                                <x-tabler.form-input type="time" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" value="{{ $oldValue }}" required="{{ $isian->is_required }}" />
                            
                            @elseif($field->type === 'file')
                                <x-tabler.form-input type="file" name="{{ $fieldName }}" label="{{ $field->nama_isian }}">
                                    @if($oldValue)
                                        <div class="mb-2">
                                            <span class="badge bg-blue-lt">File Saat Ini:</span> 
                                            <a href="{{ Storage::url($oldValue) }}" target="_blank" class="ms-1">{{ basename($oldValue) }}</a>
                                        </div>
                                    @endif
                                    <small class="form-hint">Upload file baru untuk mengganti yang lama.</small>
                                </x-tabler.form-input>
                            
                            @elseif($field->type === 'select')
                                <x-tabler.form-select name="{{ $fieldName }}" label="{{ $field->nama_isian }}" required="{{ $isian->is_required }}">
                                    <option value="">Pilih...</option>
                                    @foreach($field->type_value ?? [] as $opt)
                                        <option value="{{ $opt }}" {{ $opt == $oldValue ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </x-tabler.form-select>
                            
                            @else
                                <x-tabler.form-input type="text" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" value="{{ $oldValue }}" required="{{ $isian->is_required }}" />
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
                <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy" text="Simpan Perubahan & Ajukan Ulang" />
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
