@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('content')
@if(!(request()->ajax() || request()->has('ajax')))
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
@endif

<div class="{{ (request()->ajax() || request()->has('ajax')) ? '' : 'page-body' }}">
    <div class="{{ (request()->ajax() || request()->has('ajax')) ? '' : 'container-xl' }}">
        <div class="card {{ (request()->ajax() || request()->has('ajax')) ? 'border-0 shadow-none mb-0' : '' }}">
            @if(request()->ajax() || request()->has('ajax'))
                <div class="modal-header">
                    <h5 class="modal-title">Edit: {{ $dokSub->judul }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @else
                <div class="card-header">
                    <h3 class="card-title">Edit: {{ $dokSub->judul }}</h3>
                    <div class="card-actions">
                         <a href="{{ route('pemutu.dokumens.show', $dokSub->dok_id) }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            @endif

            <form action="{{ route('pemutu.dok-subs.update', $dokSub->doksub_id) }}" method="POST" class="ajax-form">
                @csrf
                @method('PUT')
                
                <div class="{{ (request()->ajax() || request()->has('ajax')) ? 'modal-body' : 'card-body' }}">
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

                    @php
                        $jenis = strtolower(trim($dokSub->dokumen->jenis));
                        $canProduceIndikator = in_array($jenis, ['standar', 'formulir', 'manual_prosedur', 'renop']);
                    @endphp

                    @if($canProduceIndikator)
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_hasilkan_indikator" value="1" {{ $dokSub->is_hasilkan_indikator ? 'checked' : '' }}>
                            <span class="form-check-label">Hasilkan Indikator {{ ucfirst($jenis === 'renop' ? 'renop' : 'standar') }}?</span>
                        </label>
                        <div class="text-muted small">Jika dicentang, poin ini akan memiliki tombol untuk input Indikator di halaman detail.</div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="isi" class="form-label">Konten / Isi Lengkap</label>
                        <textarea class="form-control rich-text-editor" id="isi" name="isi" rows="15">{{ $dokSub->isi }}</textarea>
                    </div>
                </div>

                <div class="{{ (request()->ajax() || request()->has('ajax')) ? 'modal-footer' : 'card-footer text-end' }}">
                    @if(request()->ajax() || request()->has('ajax'))
                        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        const initEditor = () => {
            if (window.loadHugeRTE) {
                window.loadHugeRTE('.rich-text-editor', {
                    height: 400,
                    menubar: true,
                    plugins: 'lists link table image code',
                    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image | table | code'
                });
            }
        };

        if (typeof jQuery !== 'undefined' && jQuery('.modal').is(':visible')) {
            setTimeout(initEditor, 300);
        } else {
            document.addEventListener('DOMContentLoaded', initEditor);
            initEditor(); 
        }
    })();
</script>
@endpush
@endsection
