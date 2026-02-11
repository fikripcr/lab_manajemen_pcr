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
                        
                        <div class="col-12 mb-3">
                            <label class="form-label {{ $item->is_required ? 'required' : '' }}">
                                {{ $field->nama_isian }}
                            </label>
                            
                            @if($field->type === 'textarea')
                                <textarea name="{{ $fieldName }}" class="form-control" rows="3" {{ $required }}></textarea>
                            
                            @elseif($field->type === 'select')
                                <select name="{{ $fieldName }}" class="form-select" {{ $required }}>
                                    <option value="">-- Pilih --</option>
                                    @foreach($field->type_value ?? [] as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            
                            @elseif($field->type === 'date')
                                <input type="date" name="{{ $fieldName }}" class="form-control" {{ $required }}>
                            
                            @elseif($field->type === 'number')
                                <input type="number" name="{{ $fieldName }}" class="form-control" {{ $required }}>
                            
                            @elseif($field->type === 'file')
                                <input type="file" name="{{ $fieldName }}" class="form-control" {{ $required }}>
                                <small class="text-muted">Format yang diizinkan: PDF, DOCX, JPG (Maks 2MB)</small>
                            
                            @else
                                <input type="text" name="{{ $fieldName }}" class="form-control" {{ $required }}>
                            @endif
                            
                            @if($field->keterangan_isian)
                                <small class="form-hint">{{ $field->keterangan_isian }}</small>
                            @endif
                        </div>
                    @endforeach
                    
                    <div class="col-12 mb-3">
                        <label class="form-label">Keterangan Tambahan (Opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Sampaikan pesan tambahan jika ada..."></textarea>
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
