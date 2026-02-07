@extends('layouts.admin.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">SPMI / Sub Dokumen</div>
                <h2 class="page-title">Edit Isi Dokumen</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit: {{ $dokSub->judul }}</h3>
                <div class="card-actions">
                     <a href="{{ route('pemtu.dokumens.show', $dokSub->dok_id) }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('pemtu.dok-subs.update', $dokSub->doksub_id) }}" method="POST" class="ajax-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="judul" class="form-label required">Judul / Poin</label>
                                <input type="text" class="form-control" id="judul" name="judul" value="{{ $dokSub->judul }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="seq" class="form-label">Urutan</label>
                                <input type="number" class="form-control" id="seq" name="seq" value="{{ $dokSub->seq }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="isi" class="form-label">Konten / Isi Lengkap</label>
                        <textarea class="form-control rich-text-editor" id="isi" name="isi" rows="15">{{ $dokSub->isi }}</textarea>
                    </div>

                    <div class="form-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.loadHugeRTE) {
            window.loadHugeRTE('.rich-text-editor', {
                height: 500,
                menubar: true, // Enable menu for full page
                plugins: 'lists link table image code',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image | table | code'
            });
        }
    });
</script>
@endpush
@endsection
