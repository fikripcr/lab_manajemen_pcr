@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Manajemen FAQ" pretitle="Info Publik">
    <x-slot:actions>
        <x-tabler.button 
            type="button" 
            class="ajax-modal-btn btn-primary d-none d-sm-inline-block" 
            data-url="{{ route('shared.faq.create') }}" 
            data-modal-title="Tambah FAQ" 
            icon="ti ti-plus" 
            text="Tambah FAQ" 
        />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @if($faqs->isEmpty())
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <x-tabler.empty-state
                                title="Belum ada FAQ"
                                text="Silakan tambahkan FAQ baru."
                                icon="ti ti-help-circle"
                            />
                        </div>
                    </div>
                </div>
            @else
                @foreach($faqs as $category => $items)
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ $category ?: 'Umum (Tanpa Kategori)' }}
                                    <span class="badge bg-primary-lt ms-2">{{ $items->count() }}</span>
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush sortable-list" data-category="{{ $category }}">
                                    @foreach($items as $faq)
                                        <div class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $faq->hashid }}">
                                            <div class="drag-handle cursor-move me-2">
                                                <i class="ti ti-grip-vertical text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold mb-1">{{ $faq->question }}</div>
                                                <div class="text-muted small text-truncate" style="max-width: 300px;">
                                                    {!! Str::limit(strip_tags($faq->answer), 100) !!}
                                                </div>
                                                <div class="mt-1">
                                                    @if($faq->is_active)
                                                        <span class="badge bg-success-lt">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary-lt">Draft</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="btn-list flex-nowrap ms-2">
                                                <x-tabler.button 
                                                    type="button" 
                                                    class="btn-icon btn-ghost-primary ajax-modal-btn" 
                                                    icon="ti ti-pencil" 
                                                    data-url="{{ route('shared.faq.edit', $faq->hashid) }}"
                                                    data-modal-title="Edit FAQ"
                                                    title="Edit"
                                                />
                                                <x-tabler.button 
                                                    type="button" 
                                                    class="btn-icon btn-ghost-danger ajax-delete" 
                                                    icon="ti ti-trash" 
                                                    data-url="{{ route('shared.faq.destroy', $faq->hashid) }}"
                                                    data-title="Hapus FAQ?"
                                                    title="Delete"
                                                />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sortables = document.querySelectorAll('.sortable-list');
    
    sortables.forEach(el => {
        new Sortable(el, {
            group: 'faq-list', // Allow dragging between lists
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'bg-indigo-lt',
            onEnd: function (evt) {
                saveOrder();
            }
        });
    });

    function saveOrder() {
        let orderData = {};
        
        document.querySelectorAll('.sortable-list').forEach(list => {
            let category = list.dataset.category || 'null'; // Use 'null' string for empty category
            let items = [];
            
            list.querySelectorAll('.list-group-item').forEach(item => {
                items.push(item.dataset.id);
            });
            
            if (items.length > 0) {
                orderData[category] = items;
            }
        });

        // Send to server
        axios.post('{{ route("shared.faq.reorder") }}', { order: orderData })
            .then(response => {
                showSuccessMessage('Urutan & Kategori berhasil diperbarui');
            })
            .catch(error => {
                showErrorMessage('Gagal menyimpan urutan');
                console.error(error);
            });
    }
});
</script>
@endpush
