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
                                <x-tabler.form-input name="judul" label="Judul" id="judul" value="{{ $dokSub->judul }}" required="true" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <x-tabler.form-input type="number" id="seq" name="seq" label="Urutan" value="{{ $dokSub->seq }}" />
                        </div>
                    </div>

                    @php
                        $jenis = strtolower(trim($dokSub->dokumen->jenis));
                        $canProduceIndikator = in_array($jenis, ['standar', 'formulir', 'manual_prosedur', 'renop']);
                    @endphp

                    @if($canProduceIndikator)
                    <div class="mb-3">
                        <x-tabler.form-checkbox 
                            name="is_hasilkan_indikator" 
                            label="Hasilkan Indikator {{ ucfirst($jenis === 'renop' ? 'renop' : 'standar') }}?" 
                            value="1" 
                            :checked="$dokSub->is_hasilkan_indikator" 
                            switch 
                        />
                        <div class="text-muted small">Jika dicentang, poin ini akan memiliki tombol untuk input Indikator di halaman detail.</div>
                    </div>
                    @endif

                    <x-tabler.form-textarea type="editor" name="isi" id="isi" label="Konten / Isi Lengkap" :value="$dokSub->isi" height="400" />
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
