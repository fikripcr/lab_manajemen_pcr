@use('Illuminate\Support\Facades\Storage')
@extends('layouts.tabler.app')

@php
    $isEdit = isset($layanan) && $layanan->exists;
    if ($isEdit) {
        $jenisLayanan = $layanan->jenisLayanan;
        $answers = $layanan->isians->keyBy('nama_isian');
    }
@endphp

@section('header')
<x-tabler.page-header 
    title="{{ $isEdit ? 'Edit Pengajuan: ' . $layanan->no_layanan : $pageTitle }}" 
    pretitle="{{ $isEdit ? $jenisLayanan->nama_layanan : 'Pengajuan Baru' }}">
    <x-slot:actions>
        <x-tabler.button type="back" :href="$isEdit ? route('eoffice.layanan.show', $layanan->hashid) : route('eoffice.layanan.services')" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ $isEdit ? route('eoffice.layanan.update', $layanan->encrypted_layanan_id) : route('eoffice.layanan.store') }}" 
              method="POST" class="card ajax-form" enctype="multipart/form-data">
            @csrf
            @if($isEdit) @method('PUT') @endif
            
            <input type="hidden" name="jenislayanan_id" value="{{ $jenisLayanan->jenislayanan_id }}">
            
            <div class="card-header">
                <h3 class="card-title">{{ $isEdit ? 'Form Revisi Data' : 'Formulir Pengajuan' }}</h3>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-{{ $isEdit ? '12' : '12' }}">
                        @foreach($jenisLayanan->isians->sortBy('seq') as $item)
                            @php
                                $field = $item->kategoriIsian;
                                $fieldName = 'field_' . $field->kategoriisian_id;
                                $oldValue = $isEdit ? ($answers[$field->nama_isian]->isi ?? '') : old($fieldName);
                            @endphp
                            
                            <div class="mb-3">
                                @if($field->type === 'textarea')
                                    <x-tabler.form-textarea name="{{ $fieldName }}" label="{{ $field->nama_isian }}" rows="3" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}">
                                        {{ $oldValue }}
                                    </x-tabler.form-textarea>
                                
                                @elseif($field->type === 'select')
                                    <x-tabler.form-select name="{{ $fieldName }}" label="{{ $field->nama_isian }}" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}">
                                        <option value="">-- Pilih --</option>
                                        @foreach($field->type_value ?? [] as $opt)
                                            <option value="{{ $opt }}" {{ $opt == $oldValue ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                
                                @elseif($field->type === 'date')
                                    <x-tabler.form-input type="date" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" value="{{ $oldValue }}" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}" />
                                
                                @elseif($field->type === 'time')
                                    <x-tabler.form-input type="time" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" value="{{ $oldValue }}" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}" />
                                
                                @elseif($field->type === 'number')
                                    <x-tabler.form-input type="number" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" value="{{ $oldValue }}" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}" />
                                
                                @elseif($field->type === 'file')
                                    <x-tabler.form-input type="file" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" required="{{ !$isEdit && $item->is_required }}">
                                        @if($isEdit && $oldValue)
                                            <div class="mb-2">
                                                <span class="badge bg-blue-lt">File Saat Ini:</span> 
                                                <a href="{{ Storage::url($oldValue) }}" target="_blank" class="ms-1">{{ basename($oldValue) }}</a>
                                            </div>
                                            <small class="form-hint">Upload file baru untuk mengganti yang lama.</small>
                                        @else
                                            <small class="text-muted d-block mt-1">Format yang diizinkan: PDF, DOCX, JPG (Maks 2MB)</small>
                                            @if($field->keterangan_isian)
                                                <small class="form-hint">{{ $field->keterangan_isian }}</small>
                                            @endif
                                        @endif
                                    </x-tabler.form-input>
                                
                                @else
                                    <x-tabler.form-input type="text" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" value="{{ $oldValue }}" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}" />
                                @endif

                                @if($item->info_tambahan)
                                    <small class="form-hint">{{ $item->info_tambahan }}</small>
                                @endif
                            </div>
                        @endforeach
                        
                        <div class="mb-3">
                            <x-tabler.form-textarea name="keterangan" label="{{ $isEdit ? 'Keterangan / Catatan Revisi' : 'Keterangan Tambahan (Opsional)' }}" rows="2" placeholder="Sampaikan pesan tambahan jika ada...">
                                {{ $isEdit ? $layanan->keterangan : '' }}
                            </x-tabler.form-textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer text-end">
                <x-tabler.button type="submit" icon="{{ $isEdit ? 'ti ti-device-floppy' : 'ti ti-send' }}" text="{{ $isEdit ? 'Simpan Perubahan & Ajukan Ulang' : 'Ajukan' }}" />
            </div>
        </form>
    </div>
    
    @if($isEdit)
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
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('form-success', function(e) {
        if (e.detail.redirect) {
            window.location.href = e.detail.redirect;
        }
    });
</script>
@endpush
