@extends('layouts.admin.app')
@section('title', 'Dokumen SPMI')

@section('header')
<x-tabler.page-header title="Dokumen SPMI" pretitle="Documents">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Kebijakan" class="ajax-modal-btn btn-primary" data-url="{{ route('pemutu.dokumens.create') }}" data-modal-title="Tambah Dokumen Kebijakan" />
        <x-tabler.button type="button" icon="ti ti-file-plus" text="Tambah Standar" class="ajax-modal-btn btn-success ms-2" data-url="{{ route('pemutu.dokumens.create-standar') }}" data-modal-title="Tambah Dokumen Standar" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <!-- Tree View Sidebar with Tabs -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-bottom-0">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#main-kebijakan" class="nav-link active" data-bs-toggle="tab"><i class="ti ti-gavel me-2"></i>Kebijakan</a>
                    </li>
                    <li class="nav-item">
                        <a href="#main-standar" class="nav-link" data-bs-toggle="tab"><i class="ti ti-certificate me-2"></i>Standar</a>
                    </li>
                </ul>
                <div class="card-actions">
                     <a href="{{ route('pemutu.dokumens.index') }}" class="btn btn-sm btn-ghost-secondary" title="Reset Filters">
                        <i class="ti ti-refresh"></i>
                    </a>
                </div>
            </div>

            <div class="tab-content">
                <!-- PANEL KEBIJAKAN -->
                <div class="tab-pane active show" id="main-kebijakan">
                    <!-- Filters & Sub-Tabs -->
                    <div class="card-body border-bottom bg-light-lt py-3">
                         <div class="d-flex gap-2 mb-3">
                            <select class="form-select form-select-sm" id="filter-periode" onchange="window.location.href='?periode='+this.value+'&jenis='+document.querySelector('.nav-link.active')?.dataset.jenis">
                                <option value="">Semua Periode</option>
                                @foreach($periods as $p)
                                    <option value="{{ $p }}" {{ request('periode') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control form-control-sm" id="tree-search" placeholder="Cari dokumen...">
                        </div>

                        <ul class="nav nav-pills nav-fill" data-bs-toggle="tabs">
                             <li class="nav-item">
                                <a href="#tab-visi-misi" class="nav-link py-1 {{ !request('jenis') || request('jenis') == 'visi-misi' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="visi-misi">VISI & MISI</a>
                            </li>
                            @foreach(['rjp' => 'RJP', 'renstra' => 'RENSTRA', 'renop' => 'RENOP'] as $key => $label)
                            <li class="nav-item">
                                <a href="#tab-{{ $key }}" class="nav-link py-1 {{ request('jenis') == $key ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="{{ $key }}">{{ $label }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Content Trees -->
                    <div class="card-body p-0 overflow-auto" style="max-height: 55vh;">
                         <div class="tab-content p-3">
                             <!-- VISI & MISI -->
                             <div class="tab-pane {{ !request('jenis') || request('jenis') == 'visi-misi' ? 'active show' : '' }}" id="tab-visi-misi">
                                <ul class="list-unstyled nested-sortable">
                                    @foreach($dokumentByJenis['visi'] ?? [] as $dok)
                                        @include('pages.pemutu.dokumens._tree_item', ['dok' => $dok, 'level' => 0])
                                    @endforeach
                                    @if(empty($dokumentByJenis['visi']))
                                        <li class="text-muted text-center py-3">Tidak ada dokumen VISI.</li>
                                    @endif
                                </ul>
                             </div>

                             <!-- RJP/RENSTRA/RENOP -->
                             @foreach(['rjp', 'renstra', 'renop'] as $jenis)
                             <div class="tab-pane {{ request('jenis') == $jenis ? 'active show' : '' }}" id="tab-{{ $jenis }}">
                                <ul class="list-unstyled nested-sortable">
                                    @forelse($dokumentByJenis[$jenis] ?? [] as $dok)
                                        @include('pages.pemutu.dokumens._tree_item', ['dok' => $dok, 'level' => 0, 'collapsed' => true])
                                    @empty
                                        <li class="text-muted text-center py-3">Tidak ada dokumen {{ strtoupper($jenis) }}.</li>
                                    @endforelse
                                </ul>
                             </div>
                             @endforeach
                         </div>
                    </div>
                </div>

                <!-- PANEL STANDAR -->
                <div class="tab-pane" id="main-standar">
                     <div class="card-body border-bottom bg-light-lt py-3">
                         <div class="text-muted small mb-2">Filter Jenis:</div>
                         <ul class="nav nav-pills nav-fill" data-bs-toggle="tabs">
                            <li class="nav-item"><a href="#std-standar" class="nav-link py-1 active" data-bs-toggle="tab">Standar</a></li>
                            <li class="nav-item"><a href="#std-formulir" class="nav-link py-1" data-bs-toggle="tab">Formulir</a></li>
                            <li class="nav-item"><a href="#std-sop" class="nav-link py-1" data-bs-toggle="tab">SOP</a></li>
                         </ul>
                     </div>
                     <div class="card-body p-0 overflow-auto" style="max-height: 55vh;">
                          <div class="tab-content p-3">
                               @foreach(['standar', 'formulir', 'sop'] as $stType)
                               <div class="tab-pane {{ $stType == 'standar' ? 'active show' : '' }}" id="std-{{ $stType }}">
                                    <ul class="list-unstyled nested-sortable">
                                        @php
                                            $list = $dokumentByJenis[$stType] ?? [];
                                        @endphp

                                        @forelse($list as $dok)
                                            @include('pages.pemutu.dokumens._tree_item', ['dok' => $dok, 'level' => 0, 'collapsed' => true])
                                        @empty
                                            <li class="text-muted text-center py-3">Tidak ada dokumen {{ ucfirst($stType) }}.</li>
                                        @endforelse
                                    </ul>
                               </div>
                               @endforeach
                          </div>
                     </div>
                </div>
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
        function initializeDragAndDrop() {
            // Clear previous instances to avoid duplicates
            const nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));

            // Remove any existing sortable instances
            nestedSortables.forEach(function (el) {
                if (el.sortableInstance) {
                    el.sortableInstance.destroy();
                }
            });

            function getHierarchyFromUl(ul) {
                const items = [];
                Array.from(ul.children).forEach(li => {
                    const id = li.dataset.id;
                    if (!id) return;

                    const item = { id: id, name: li.querySelector('.tree-item-name')?.textContent || '' };
                    const nestedUl = li.querySelector('ul.nested-sortable');
                    if (nestedUl && nestedUl.children.length > 0) {
                        item.children = getHierarchyFromUl(nestedUl);
                    }
                    items.push(item);
                });
                return items;
            }

            function saveHierarchy() {
                // Find the currently active tab's tree
                const activeTabPane = document.querySelector('.tab-pane.active.show');
                const rootUl = activeTabPane ? activeTabPane.querySelector('.nested-sortable') : null;

                if (!rootUl) {
                    console.warn('No active tree found to save');
                    return;
                }

                const hierarchy = getHierarchyFromUl(rootUl);

                console.log('Saving hierarchy...', hierarchy);

                axios.post("{{ route('pemutu.dokumens.reorder') }}", { hierarchy: hierarchy })
                    .then(response => {
                    })
                    .catch(error => {
                        console.error('Failed to save hierarchy', error);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal menyimpan urutan dokumen. Silakan coba lagi.'
                            });
                        } else {
                            alert('Gagal menyimpan urutan baru. Silakan refresh halaman.');
                        }
                    });
            }

            nestedSortables.forEach(function (el) {
                // Create new sortable instance
                el.sortableInstance = new Sortable(el, {
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    handle: '.d-flex', // Using the flex container as the drag handle
                    onEnd: function (evt) {
                        saveHierarchy();
                    }
                });
            });
        }

        // Initialize drag and drop on page load
        initializeDragAndDrop();

        // Reinitialize when tabs change
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            setTimeout(initializeDragAndDrop, 100); // Small delay to ensure DOM is updated
        });

        // --- Existing Logic ---

        // Handle Tree Item Click
        $(document).on('click', '.tree-item-link', function(e) {
            e.preventDefault();
            $('.tree-item-link').removeClass('fw-bold text-primary');
            $(this).addClass('fw-bold text-primary');

            const url = $(this).data('url');
            const docJenis = $(this).data('jenis'); // Get the document type directly

            // Check if this is a RENOP document
            let detailUrl = url;
            if (docJenis === 'renop') {
                // Extract document ID from the URL
                const docId = url.match(/\/(\d+)$/)?.[1];
                if (docId) {
                    detailUrl = "{{ route('pemutu.dokumens.show-renop-with-indicators', ':id') }}".replace(':id', docId);
                }
            }

            $('#document-detail-panel').html('<div class="card-body text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');

            axios.get(detailUrl)
                .then(function(response) {
                    $('#document-detail-panel').html(response.data);
                })
                .catch(function(error) {
                    console.error(error);
                    $('#document-detail-panel').html('<div class="card-body text-center text-danger">Gagal memuat detail data.</div>');
                });
        });

        // Toggle children (Standard Tree)
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

        // Toggle children (Custom for VISI/MISI Root)
        $(document).on('click', '.tree-toggle-custom', function(e) {
             e.preventDefault();
             e.stopPropagation();
             const parentLi = $(this).closest('li');
             const target = parentLi.children('ul');
             const icon = $(this).find('i');

             target.toggleClass('d-none');

             if (target.hasClass('d-none')) {
                 icon.removeClass('ti-chevron-down').addClass('ti-chevron-right');
             } else {
                 icon.removeClass('ti-chevron-right').addClass('ti-chevron-down');
             }
        });

        // Simple Search Filter - Fixed to work with actual tree structure
        $('#tree-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();

            // First hide all items
            $('.nested-sortable li').hide();

            // Then show items that match the search term
            $('.nested-sortable li').each(function() {
                var text = $(this).find('.tree-item-name').text().toLowerCase();
                if (text.indexOf(value) > -1) {
                    // Show this item and all its parents
                    $(this).show().parents('li').show();

                    // Also show all children of matching items
                    $(this).find('li').show();
                }
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

    /* Drag handle styling */
    .d-flex[draggable] {
        cursor: move;
    }

    .d-flex[draggable]:hover {
        background-color: rgba(0, 105, 217, 0.06);
    }

    /* Tree item name styling */
    .tree-item-name {
        cursor: pointer;
    }
</style>
@endpush
