@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Manajemen FAQ" pretitle="Info Publik">
    <x-slot:actions>
        <x-tabler.button href="#" class="btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modalAction" data-url="{{ route('shared.faq.create') }}" icon="ti ti-plus" text="Tambah FAQ" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                @if($faqs->isEmpty())
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-help-circle fs-1"></i>
                        </div>
                        <p class="empty-title">Belum ada FAQ</p>
                        <p class="empty-subtitle text-secondary">
                            Silakan tambahkan FAQ baru.
                        </p>
                    </div>
                @else
                    <div class="accordion" id="faq-accordion">
                        @foreach($faqs as $category => $items)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ Str::slug($category) }}">
                                    <button class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($category) }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                        {{ $category ?: 'Umum (Tanpa Kategori)' }} 
                                        <span class="badge bg-primary-lt ms-2">{{ $items->count() }}</span>
                                    </button>
                                </h2>
                                <div id="collapse-{{ Str::slug($category) }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#faq-accordion">
                                    <div class="accordion-body pt-0">
                                        <div class="list-group list-group-flush">
                                            @foreach($items as $faq)
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
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
                                                            class="btn-icon btn-ghost-primary" 
                                                            icon="ti ti-pencil" 
                                                            onclick="openModal('{{ route('shared.faq.edit', $faq->hashid) }}', 'Edit FAQ')"
                                                            title="Edit"
                                                        />
                                                        <x-tabler.button 
                                                            type="button" 
                                                            class="btn-icon btn-ghost-danger" 
                                                            icon="ti ti-trash" 
                                                            onclick="deleteData('{{ route('shared.faq.destroy', $faq->hashid) }}')"
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
