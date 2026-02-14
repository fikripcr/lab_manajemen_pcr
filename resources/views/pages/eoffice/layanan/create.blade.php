@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="Pengajuan Baru">
    <x-slot:actions>
        <a href="{{ route('eoffice.layanan.services') }}" class="btn btn-link link-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('eoffice.layanan.store') }}" method="POST" class="card ajax-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenislayanan_id" value="{{ $jenisLayanan->jenislayanan_id }}">
            
            <div class="card-header">
                <h3 class="card-title">Formulir Pengajuan</h3>
            </div>
            
            <div class="card-body">
                <div class="row">
                    @foreach($jenisLayanan->isians->sortBy('seq') as $item)
                        @php
                            $field = $item->kategoriIsian;
                            $fieldName = 'field_' . $field->kategoriisian_id;
                            $required = $item->is_required ? 'required' : '';
                        @endphp
                        
                        <div class="col-12">
                            @if($field->type === 'textarea')
                                <x-tabler.form-textarea name="{{ $fieldName }}" label="{{ $field->nama_isian }}" rows="3" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}" />
                            
                            @elseif($field->type === 'select')
                                <x-tabler.form-select name="{{ $fieldName }}" label="{{ $field->nama_isian }}" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}">
                                    <option value="">-- Pilih --</option>
                                    @foreach($field->type_value ?? [] as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </x-tabler.form-select>
                            
                            @elseif($field->type === 'date')
                                <x-tabler.form-input type="date" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}" />
                            
                            @elseif($field->type === 'number')
                                <x-tabler.form-input type="number" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}" />
                            
                            @elseif($field->type === 'file')
                                <x-tabler.form-input type="file" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" required="{{ $item->is_required }}">
                                    <small class="text-muted d-block mt-1">Format yang diizinkan: PDF, DOCX, JPG (Maks 2MB)</small>
                                    @if($field->keterangan_isian)
                                        <small class="form-hint">{{ $field->keterangan_isian }}</small>
                                    @endif
                                </x-tabler.form-input>
                            
                            @else
                                <x-tabler.form-input type="text" name="{{ $fieldName }}" label="{{ $field->nama_isian }}" required="{{ $item->is_required }}" help="{{ $field->keterangan_isian }}" />
                            @endif
                        </div>
                    @endforeach
                    
                    <div class="col-12">
                        <x-tabler.form-textarea name="keterangan" label="Keterangan Tambahan (Opsional)" rows="2" placeholder="Sampaikan pesan tambahan jika ada..." />
                    </div>
                </div>
            </div>
            
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-send me-1"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
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
