@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Manajemen Slideshow" pretitle="Info Publik">
    <x-slot:actions>
        <x-tabler.button 
            type="button" 
            class="ajax-modal-btn btn-primary d-none d-sm-inline-block" 
            data-url="{{ route('shared.slideshow.create') }}" 
            data-modal-title="Tambah Slideshow"
            icon="ti ti-plus" 
            text="Tambah Slideshow" 
        />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

    @if($slideshows->isEmpty())
        <x-tabler.empty-state
            title="Belum ada Slideshow"
            text="Silakan tambahkan slideshow baru."
            icon="ti ti-photo"
        >
            <x-slot:action>
                <x-tabler.button 
                    type="button" 
                    class="ajax-modal-btn btn-primary" 
                    data-url="{{ route('shared.slideshow.create') }}" 
                    data-modal-title="Tambah Slideshow"
                    icon="ti ti-plus" 
                    text="Tambah Slideshow" 
                />
            </x-slot:action>
        </x-tabler.empty-state>
    @else
        <div class="row row-cards" id="slideshow-grid">
            @foreach($slideshows as $slide)
                <div class="col-md-6 col-lg-4" data-id="{{ $slide->encrypted_slideshow_id }}">
                    <div class="card card-sm">
                        <div class="d-block">
                            @if($slide->hasMedia('slideshow_image'))
                                <img src="{{ $slide->image_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $slide->title }}">
                            @else
                                <x-tabler.empty-state
                                    title=""
                                    icon="ti ti-photo-off"
                                    class="card-img-top bg-muted-lt"
                                />
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="subheader">Urutan: {{ $slide->seq }}</div>
                                <div class="ms-auto">
                                    @if($slide->is_active)
                                        <span class="badge bg-success-lt">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-lt">Draft</span>
                                    @endif
                                </div>
                            </div>
                            <h3 class="card-title mb-1">
                                {{ $slide->title ?: 'Tanpa Judul' }}
                            </h3>
                            @if($slide->caption)
                                <div class="text-secondary small text-truncate">{{ Str::limit($slide->caption, 50) }}</div>
                            @endif
                        </div>
                        <div class="card-footer d-flex py-3">
                            <span class="cursor-move text-secondary me-auto" title="Drag to reorder">
                                <i class="ti ti-grid-dots fs-2"></i>
                            </span>
                            <div class="btn-list">
                                <x-tabler.button 
                                    type="button" 
                                    class="btn-icon btn-ghost-primary ajax-modal-btn" 
                                    icon="ti ti-pencil" 
                                    data-url="{{ route('shared.slideshow.edit', $slide->encrypted_slideshow_id) }}"
                                    data-modal-title="Edit Slideshow"
                                    title="Edit"
                                />
                                <x-tabler.button 
                                    type="button" 
                                    class="btn-icon btn-ghost-danger ajax-delete" 
                                    icon="ti ti-trash" 
                                    data-url="{{ route('shared.slideshow.destroy', $slide->encrypted_slideshow_id) }}"
                                    data-title="Hapus Slideshow?"
                                    title="Delete"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('slideshow-grid');
        if(el && window.Sortable){
            Sortable.create(el, {
                animation: 150,
                handle: '.cursor-move',
                ghostClass: 'bg-indigo-lt',
                onEnd: function (evt) {
                    saveOrder();
                }
            });
        }
    });

    function saveOrder() {
        var order = [];
        document.querySelectorAll('#slideshow-grid > div').forEach(function (el) {
            order.push(el.getAttribute('data-id'));
        });

        // Use global showLoadingMessage if available, or fallback
        if(typeof showLoadingMessage === 'function') showLoadingMessage('Menyimpan urutan...');

        axios.post('{{ route('shared.slideshow.reorder') }}', {
            order: order,
            _token: '{{ csrf_token() }}'
        })
        .then(function (response) {
            if(window.Swal) Swal.close();
            
            if(response.data.status === 'success'){
                if(typeof showSuccessMessage === 'function') {
                    showSuccessMessage(response.data.message);
                } else {
                    // Fallback
                    console.log('Success:', response.data.message);
                }
            } else {
                if(typeof showErrorMessage === 'function') {
                    showErrorMessage(response.data.message);
                } else {
                    alert('Gagal: ' + response.data.message);
                }
            }
        })
        .catch(function (error) {
            if(window.Swal) Swal.close();
            if(typeof showErrorMessage === 'function') {
                showErrorMessage('Error!', 'Terjadi kesalahan saat menyimpan urutan.');
            } else {
                alert('Error processing request');
            }
            console.error(error);
        });
    }
</script>
@endpush
@endsection
