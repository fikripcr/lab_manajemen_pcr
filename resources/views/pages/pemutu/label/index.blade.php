@extends('layouts.tabler.app')
@section('title', 'Master Label')

@section('header')
<x-tabler.page-header title="Master Label" pretitle="Data Master">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" data-url="{{ route('pemutu.label.create') }}" data-modal-title="Tambah Label Baru" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    @foreach($parents as $parent)
    <div class="col-lg-6 col-xl-4">
        <x-tabler.card>
            <x-tabler.card-header>
                <x-slot:title>
                    <span class="d-flex align-items-center gap-2">
                        <span class="avatar avatar-xs rounded bg-{{ $parent->color ?? 'blue' }}"></span>
                        {{ $parent->name }}
                    </span>
                </x-slot:title>
                <x-slot:actions>
                    <x-tabler.dropdown>
                        <x-tabler.dropdown-item icon="ti ti-plus text-success" label="Tambah Label" 
                            class="ajax-modal-btn" 
                            url="{{ route('pemutu.label.create', ['parent_id' => $parent->encrypted_label_id]) }}" 
                            data-title="Tambah Label di {{ $parent->name }}" />
                        <x-tabler.dropdown-item type="edit" 
                            url="{{ route('pemutu.label.edit', $parent->encrypted_label_id) }}" 
                            data-title="Edit {{ $parent->name }}" />
                        <x-tabler.dropdown-divider />
                        <x-tabler.dropdown-item type="delete" 
                            url="{{ route('pemutu.label.destroy', $parent->encrypted_label_id) }}" 
                            data-text="Semua label di dalam grup ini juga akan terhapus." />
                    </x-tabler.dropdown>
                </x-slot:actions>
            </x-tabler.card-header>
            <x-tabler.card-body class="p-3">
                @if($parent->children && $parent->children->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($parent->children as $child)
                            <div class="d-inline-flex align-items-center">
                                <span class="badge bg-{{ $child->color ?? $parent->color ?? 'blue' }}-lt text-{{ $child->color ?? $parent->color ?? 'blue' }} fs-6 fw-medium px-3 py-2">
                                    {{ $child->name }}
                                </span>
                                <x-tabler.dropdown>
                                    <x-tabler.dropdown-item type="edit" 
                                        url="{{ route('pemutu.label.edit', $child->encrypted_label_id) }}" 
                                        data-title="Edit Label" />
                                    <x-tabler.dropdown-divider />
                                    <x-tabler.dropdown-item type="delete" 
                                        url="{{ route('pemutu.label.destroy', $child->encrypted_label_id) }}" />
                                </x-tabler.dropdown>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="ti ti-tags opacity-50 d-block mb-1"></i>
                        <small>Label tidak memiliki sub label (Label Tunggal)</small>
                    </div>
                @endif
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
    @endforeach

    @if($parents->isEmpty())
    <div class="col-12">
        <x-tabler.card>
            <x-tabler.card-body class="text-center py-5">
                <i class="ti ti-tags fs-1 text-muted opacity-50 d-block mb-2"></i>
                <p class="text-muted mb-3">Belum ada grup label. Buat grup label pertama Anda.</p>
                <x-tabler.button type="create" class="ajax-modal-btn" data-url="{{ route('pemutu.label.create') }}" data-modal-title="Tambah Label Baru" />
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('form-success', function(e) {
        if (!e.detail || !e.detail.redirect) {
            location.reload();
        }
    });
</script>
@endpush
