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
<div class="card overflow-hidden">
        <div class="card">
            <div class="card-body">
                @if($faqs->isEmpty())
                    <x-tabler.empty-state
                        title="Belum ada FAQ"
                        text="Silakan tambahkan FAQ baru."
                        icon="ti ti-help-circle"
                    />
                @else
                    <div class="accordion" id="faq-accordion">
                        @foreach($faqs as $category => $items)
                            <div class="accordion-item" data-category="{{ $category }}">
                                <h2 class="accordion-header" id="heading-{{ Str::slug($category) }}">
                                    <button class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($category) }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                        {{ $category ?: 'Umum (Tanpa Kategori)' }} 
                                        <span class="badge bg-primary-lt ms-2">{{ $items->count() }}</span>
                                    </button>
                                </h2>
                                <div id="collapse-{{ Str::slug($category) }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#faq-accordion">
                                    <div class="accordion-body pt-0">
                                        <div class="list-group list-group-flush sortable-list" data-category="{{ $category }}">
                                            @foreach($items as $faq)
                                                <div class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $faq->hashid }}">
                                                    <div class="drag-handle cursor-move me-2">
                                                        <i class="ti ti-grip-vertical text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold mb-1">{{ $faq->question }}</div>
                                                        <div class="text-muted small text-truncate" style="max-width: 600px;">
                                                            {!! Str::limit(strip_tags($faq->answer), 150) !!}
                                                        </div>
                                                        <div class="mt-1">
                                                            @if($faq->is_active)
                                                                <span class="badge bg-success-lt">Aktif</span>
                                                            @else
                                                                <span class="badge bg-secondary-lt">Draft</span>
                                                            @endif
                                                            <span class="text-muted small ms-2">Urutan: {{ $faq->seq }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="btn-list flex-nowrap">
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
                    </div>
                @endif
            </div>
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
