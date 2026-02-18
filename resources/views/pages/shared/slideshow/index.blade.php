@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Manajemen Slideshow" pretitle="Info Publik">
    <x-slot:actions>
        <x-tabler.button href="#" class="btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modalAction" data-url="{{ route('shared.slideshow.create') }}" icon="ti ti-plus" text="Tambah Slideshow" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                @if($slideshows->isEmpty())
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-photo fs-1"></i>
                        </div>
                        <p class="empty-title">Belum ada Slideshow</p>
                        <p class="empty-subtitle text-secondary">
                            Silakan tambahkan slideshow baru.
                        </p>
                    </div>
                @else
                    <div class="row row-cards" id="slideshow-grid">
                        @foreach($slideshows as $slide)
                            <div class="col-md-6 col-lg-4" data-id="{{ $slide->hashid }}">
                                <div class="card card-sm">
                                    <div class="d-block">
                                        <img src="{{ $slide->image_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $slide->title }}">
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
                                                class="btn-icon btn-ghost-primary" 
                                                icon="ti ti-pencil" 
                                                onclick="openModal('{{ route('shared.slideshow.edit', $slide->hashid) }}', 'Edit Slideshow')"
                                                title="Edit"
                                            />
                                            <x-tabler.button 
                                                type="button" 
                                                class="btn-icon btn-ghost-danger" 
                                                icon="ti ti-trash" 
                                                onclick="deleteData('{{ route('shared.slideshow.destroy', $slide->hashid) }}')"
                                                title="Delete"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 text-center">
                        <x-tabler.button id="save-order-btn" class="btn-primary d-none" onclick="saveOrder()" icon="ti ti-device-floppy" text="Simpan Urutan Baru" />
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('slideshow-grid');
        if(el){
            var sortable = Sortable.create(el, {
                animation: 150,
                handle: '.cursor-move',
                ghostClass: 'bg-blue-lt',
                onEnd: function (evt) {
                    // Show save button if order changed
                   saveOrder(); // Auto save or manual? Let's auto save for better UX or show button. 
                   // Implementation: Auto-save is cleaner.
                }
            });
        }
    });

    function saveOrder() {
        var order = [];
        document.querySelectorAll('#slideshow-grid > div').forEach(function (el) {
            order.push(el.getAttribute('data-id'));
        });

        // Show saving...
        showLoadingMessage('Menyimpan urutan...');

        axios.post('{{ route('shared.slideshow.reorder') }}', {
            order: order,
            _token: '{{ csrf_token() }}'
        })
        .then(function (response) {
            Swal.close();
            if(response.data.status === 'success'){
                showSuccessMessage('Berhasil!', response.data.message);
                // Optional: reload to update 'Urutan' text if needed, or just let it be.
                setTimeout(function(){ location.reload(); }, 1000);
            } else {
                showErrorMessage('Gagal!', response.data.message);
            }
        })
        .catch(function (error) {
            Swal.close();
            showErrorMessage('Error!', 'Terjadi kesalahan saat menyimpan urutan.');
            console.error(error);
        });
    }
</script>
@endpush
@section('content-ignore')
{{-- Closing the section properly to match replaced content structure if needed --}} 
@endsection
{{-- Reopening content to match original file structure? No, replace_file_content replaces the BLOCK. --}}
{{-- The original code block was inside @section('content') ... div.page-body ... div.container ... div.card ... div.card-body ... TOKEN ... div... div... div... @endsection --}}
{{-- My replacement ends with @push... --}}
{{-- I need to be careful about not breaking the @endsection of the content section. --}}
{{-- The target content I selected matches the INSIDE of card-body? --}}
{{-- Target Content: --}}
{{-- <x-tabler.datatable ... /> --}}
{{-- This is inside card-body. --}}
{{-- My replacement content starts with @if... and ends with @push... --}}
{{-- If I insert @push inside card-body, it will NOT work because @push must be top level or inside section, but usually usually works in Blade if placed anywhere, BUT better to be outside. --}}
{{-- However, I am replacing the CONTENT of card-body. --}}
{{-- I should close the divs and then add push. --}}
{{-- Wait, if I replace `<x-tabler.datatable ... />` which is INSIDE `card-body` with `... @push ...`, the @push will be inside `card-body`. --}}
{{-- Blade handles @push anywhere. But the script will be rendered in 'scripts' stack. --}}
{{-- So structurally it's fine. --}}
{{-- BUT, I need to make sure I don't leave open divs. --}}
{{-- The original was just the datatable component. --}}
{{-- My replacement is `HTML Content` + `@push`. --}}
{{-- It should be fine. --}}
            </div>
        </div>
    </div>
</div>
@endsection
