@extends('layouts.tabler.app')
@section('title', 'Master Label')

@section('header')
<x-tabler.page-header title="Master Label" pretitle="Data Master">
    <x-slot:actions>
        {{-- Global Add Label Button (Optional, or rely on sidebar/table actions) --}}
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <!-- Sidebar: Label Types -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tipe Label</h3>
                <div class="card-actions">
                    {{-- User reported button invisible. Removing btn-icon might help if size is issue, or ensuring it has content if icon-only --}}
                    <x-tabler.button type="button" icon="ti ti-plus" class="btn-sm ajax-modal-btn" data-url="{{ route('pemutu.label-types.create') }}" data-modal-title="Tambah Tipe Label" />
                </div>
            </div>
            <div class="list-group list-group-flush" id="label-type-list">
                <a href="#" class="list-group-item list-group-item-action active d-flex align-items-center" data-type-id="" onclick="filterLabels(this, '')">
                    All Labels
                    <span class="ms-auto badge bg-secondary-lt">{{ \App\Models\Pemutu\Label::count() }}</span>
                </a>
                @foreach($types as $type)
                    <div class="list-group-item list-group-item-action flex-column align-items-start label-type-item py-2" data-type-id="{{ $type->labeltype_id }}" onclick="filterLabels(this, '{{ $type->labeltype_id }}', event)">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <span class="d-flex align-items-center gap-2 text-truncate fw-bold" style="cursor: pointer;">
                                <span class="avatar avatar-xs rounded bg-{{ $type->color ?? 'blue' }}"></span>
                                {{ $type->name }}
                            </span>
                            <div class="d-flex gap-1">
                                 <x-tabler.button type="button" iconOnly class="btn-ghost-secondary ajax-modal-btn" 
                                    data-url="{{ route('pemutu.label-types.edit', $type->labeltype_id) }}" 
                                    data-modal-title="Edit Tipe" icon="ti ti-pencil" />
                                 <x-tabler.button type="button" iconOnly class="btn-ghost-danger ajax-delete" 
                                    data-url="{{ route('pemutu.label-types.destroy', $type->labeltype_id) }}" 
                                    data-title="Hapus Tipe?" 
                                    data-text="Menghapus tipe akan menghapus semua label di dalamnya." icon="ti ti-trash" />
                            </div>
                        </div>
                        @if($type->description)
                            <small class="text-muted text-truncate w-100" style="display: block;">{{ $type->description }}</small>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Content: Labels -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" id="labels-title">Daftar Label</h3>
                <div class="card-actions">
                    <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Label" class="btn-primary ajax-modal-btn" id="btn-add-label" data-url="{{ route('pemutu.labels.create') }}" data-modal-title="Tambah Label Baru" />
                </div>
            </div>
            <div class="card-body p-0">
                <x-tabler.datatable
                    id="table-labels"
                    route="{{ route('pemutu.labels.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                        ['data' => 'name', 'name' => 'name', 'title' => 'Nama Label', 'class' => 'text-center'],
                        ['data' => 'description', 'name' => 'description', 'title' => 'Deskripsi', 'class' => 'text-center'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '15%']
                    ]"
                />
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentTypeId = '';

    function filterLabels(element, typeId, event) {
        // Prevent if clicked on button group or buttons
        if (event && $(event.target).closest('.btn-icon').length) return;

        $('#label-type-list .list-group-item').removeClass('active');
        // If it's the "All" link or a specific type item (which is now a div for type items, but a tag for 'All')
        // We can just add active class to the element passed.
        // Wait, the element passed is the div/a itself.
        
        // Remove active from all items in list-group
        $('#label-type-list .list-group-item').removeClass('active');
        
        $(element).addClass('active');

        currentTypeId = typeId;
        
        // Reload DataTable
        const table = $('#table-labels').DataTable();
        const url = "{{ route('pemutu.labels.data') }}?type_id=" + typeId;
        table.ajax.url(url).load();

        // Update Add Label Button URL
        const addBtn = $('#btn-add-label');
        let addUrl = "{{ route('pemutu.labels.create') }}";
        if(typeId) {
            addUrl += "?type_id=" + typeId;
        }
        addBtn.attr('data-url', addUrl);
    }

    // Creating a listener for global modal success events to reload this page/list
    // If the unified layout handles 'form-success' event, we can listen to it.
    document.addEventListener('form-success', function(e) {
        // If a LabelType was modified, we should reload the whole page to refresh the sidebar list
        // If a Label was modified, we just reload the table.
        // How to distinguish? maybe check the URL or response?
        // Simple approach: Reload page if it's a LabelType operation.
        // Or simpler: Just reload page for everything here since it's Master data and not frequent.
        // But user asked "jika menghapus tipe label jgn lupa reload".
        
        // Let's reload page if the modal title contained "Tipe" or we can just reload page always for simplicity in this specific view
        // to ensure sidebar counts and descriptions update.
        // Or we can be smarter.
        
        if (e.detail && e.detail.redirect) {
            // If response has redirect, it might handle it.
        } else {
             // If manual control needed
             // For now, let's reload window if we can't be sure, or just reload table.
             // But for LabelType, we need to reload the DOM list. 
             // Simplest: location.reload() for LabelType changes.
             // We can check if the form action url contained 'label-types'.
             if(e.detail.url && e.detail.url.includes('label-types')) {
                 location.reload();
             } else {
                 $('#table-labels').DataTable().ajax.reload();
             }
        }
    });
</script>
<style>
    .hover-opacity-100 { opacity: 0; transition: opacity 0.2s; }
    .label-type-item:hover .hover-opacity-100 { opacity: 1; }
</style>
@endpush
