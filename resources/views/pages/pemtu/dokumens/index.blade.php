@extends('layouts.admin.app')
@section('title', 'Dokumen SPMI')

@section('header')
<x-tabler.page-header title="Dokumen SPMI" pretitle="Documents">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Add Document" class="ajax-modal-btn" data-url="{{ route('pemtu.dokumens.create') }}" data-modal-title="Add New Document" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <!-- Tree View Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header flex-column align-items-stretch">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="card-title">Struktur Dokumen</h3>
                    <div class="card-actions">
                        {{-- Filter Toggle or Reset --}}
                        <a href="{{ route('pemtu.dokumens.index') }}" class="btn btn-sm btn-ghost-secondary" title="Reset Filters">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="filter-periode" onchange="window.location.href='?periode='+this.value">
                        <option value="">Semua Periode</option>
                        @foreach($periods as $p)
                            <option value="{{ $p }}" {{ request('periode') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                    <input type="text" class="form-control form-control-sm" id="tree-search" placeholder="Cari dokumen...">
                </div>
            </div>
            <div class="card-body overflow-auto" style="max-height: 70vh;">
                <ul class="list-unstyled" id="dokumen-tree">
                            @forelse($dokumens as $dok)
                                @include('pages.pemtu.dokumens._tree_item', ['dok' => $dok, 'level' => 0])
                            @empty
                                <li class="text-muted text-center py-3">Tidak ada dokumen.</li>
                            @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Detail Panel -->
    <div class="col-lg-8">
        <div class="card" id="document-detail-panel">
            <div class="card-body text-center py-5">
                <p class="text-muted">Pilih dokumen untuk melihat detail.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Drag and Drop Logic ---
        const nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable, #dokumen-tree'));

        function getHierarchyFromUl(ul) {
            const items = [];
            Array.from(ul.children).forEach(li => {
                const id = li.dataset.id;
                if (!id) return;
                
                const item = { id: id };
                const nestedUl = li.querySelector('ul.nested-sortable');
                if (nestedUl && nestedUl.children.length > 0) {
                    item.children = getHierarchyFromUl(nestedUl);
                }
                items.push(item);
            });
            return items;
        }

        function saveHierarchy() {
            const rootUl = document.getElementById('dokumen-tree');
            const hierarchy = getHierarchyFromUl(rootUl);
            
            console.log('Saving hierarchy...', hierarchy);
            
            axios.post("{{ route('pemtu.dokumens.reorder') }}", { hierarchy: hierarchy })
                .then(response => {
                    console.log('Hierarchy saved');
                })
                .catch(error => {
                    console.error('Failed to save hierarchy', error);
                    alert('Gagal menyimpan urutan baru. Silakan refresh halaman.');
                });
        }

        nestedSortables.forEach(function (el) {
            new Sortable(el, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                handle: '.d-flex',
                onEnd: function (evt) {
                    saveHierarchy();
                }
            });
        });

        // --- Existing Logic ---

        // Handle Tree Item Click
        $(document).on('click', '.tree-item-link', function(e) {
            e.preventDefault();
            $('.tree-item-link').removeClass('fw-bold text-primary');
            $(this).addClass('fw-bold text-primary');

            const url = $(this).data('url');
            $('#document-detail-panel').html('<div class="card-body text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');

            axios.get(url)
                .then(function(response) {
                    $('#document-detail-panel').html(response.data);
                })
                .catch(function(error) {
                    console.error(error);
                    $('#document-detail-panel').html('<div class="card-body text-center text-danger">Gagal memuat detail data.</div>');
                });
        });

        // Toggle children
        $(document).on('click', '.tree-toggle', function(e) {
             e.preventDefault();
             e.stopPropagation();
             const target = $(this).closest('li').children('ul');
             const icon = $(this).find('i');
             
             target.toggleClass('d-none');
             
             if (target.hasClass('d-none')) {
                 icon.removeClass('ti-chevron-down').addClass('ti-chevron-right');
             } else {
                 icon.removeClass('ti-chevron-right').addClass('ti-chevron-down');
             }
        });

        // Simple Search Filter
        $('#tree-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $("#dokumen-tree li").filter(function() {
                // Check if current li has text match (excluding children text if possible? No, filter hides li)
                // Text includes children text in jQuery .text()
                // Better approach: toggle visibility based on direct text or keep visible if child visible?
                // For simplicity, existing logic:
                var text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(value) > -1)
            });
        });
    });
</script>
<style>
    .tree-item-link { cursor: pointer; text-decoration: none; color: inherit; }
    .tree-item-link:hover { color: var(--tblr-primary); font-weight: 500; }
    
    /* Tree Lines */
    ul#dokumen-tree, ul.nested-sortable { list-style: none; padding-left: 20px; }
    ul.nested-sortable { margin-left: 5px; border-left: 1px dashed #e6e7e9; min-height: 10px; }
    
    li[data-id] { position: relative; }
    li[data-id]::before {
        content: "";
        position: absolute;
        top: 15px; 
        left: -20px;
        width: 15px;
        border-top: 1px dashed #e6e7e9;
    }
    
    ul#dokumen-tree > li[data-id]::before { display: none; }
    ul#dokumen-tree > li[data-id] { padding-left: 0; }
    
    .tree-toggle { cursor: pointer; width: 20px; display: inline-block; text-align: center; color: #6e7582; }
    .tree-toggle:hover { color: var(--tblr-primary); }
    
    .sortable-ghost { opacity: 0.4; background-color: #f1f5f9; }
</style>
@endpush
