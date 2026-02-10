@extends('layouts.admin.app')
@section('title', 'Dokumen SPMI')

@section('header')
<x-tabler.page-header title="Dokumen SPMI" pretitle="Berkas">
    <x-slot:actions>
        @if($activeTab === 'standar')
            <x-tabler.button type="button" icon="ti ti-file-plus" text="Tambah Standar" class="ajax-modal-btn btn-success" data-url="{{ route('pemutu.dokumens.create-standar') }}" data-modal-title="Tambah Dokumen Standar" />
        @else
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Kebijakan" class="ajax-modal-btn btn-primary" data-url="{{ route('pemutu.dokumens.create', ['tabs' => $activeTab]) }}" data-modal-title="Tambah Dokumen Kebijakan" />
        @endif
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <!-- Tree View Sidebar with Tabs -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-bottom-0">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a href="{{ route('pemutu.dokumens.index', ['tabs' => 'kebijakan']) }}" class="nav-link {{ $activeTab === 'kebijakan' ? 'active' : '' }}"><i class="ti ti-gavel me-2"></i>Kebijakan</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pemutu.dokumens.index', ['tabs' => 'standar']) }}" class="nav-link {{ $activeTab === 'standar' ? 'active' : '' }}"><i class="ti ti-certificate me-2"></i>Standar</a>
                    </li>
                </ul>
                <div class="card-actions">
                     <a href="{{ route('pemutu.dokumens.index', ['tabs' => $activeTab]) }}" class="btn btn-sm btn-ghost-secondary" title="Reset Filters">
                        <i class="ti ti-refresh"></i>
                    </a>
                </div>
            </div>

            <div class="tab-content">
                @if($activeTab === 'kebijakan')
                <!-- PANEL KEBIJAKAN -->
                <div class="tab-pane active show" id="main-kebijakan">
                    <!-- Filters & Sub-Tabs -->
                    <div class="card-body border-bottom bg-light-lt py-3">
                         <div class="d-flex gap-2 mb-3">
                             <select class="form-select form-select-sm filter-sync-param" data-param="periode" data-base-url="{{ route('pemutu.dokumens.index') }}">
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
                @else
                <!-- PANEL STANDAR -->
                <div class="tab-pane active show" id="main-standar">
                     <div class="card-body border-bottom bg-light-lt py-3">
                         <div class="d-flex gap-2 mb-3">
                             <select class="form-select form-select-sm filter-sync-param" data-param="periode" data-base-url="{{ route('pemutu.dokumens.index') }}">
                                <option value="">Semua Periode</option>
                                @foreach($periods as $p)
                                    <option value="{{ $p }}" {{ request('periode') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control form-control-sm" id="tree-search" placeholder="Cari dokumen...">
                         </div>
                         <ul class="nav nav-pills nav-fill" data-bs-toggle="tabs">
                            <li class="nav-item"><a href="#std-standar" class="nav-link py-1 {{ (!request('jenis') && $activeTab === 'standar') || request('jenis') == 'standar' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="standar">Standar</a></li>
                            <li class="nav-item"><a href="#std-formulir" class="nav-link py-1 {{ request('jenis') == 'formulir' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="formulir">Formulir</a></li>
                            <li class="nav-item"><a href="#std-manual_prosedur" class="nav-link py-1 {{ request('jenis') == 'manual_prosedur' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="manual_prosedur">Manual Prosedur</a></li>
                         </ul>
                     </div>
                     <div class="card-body p-0 overflow-auto" style="max-height: 55vh;">
                          <div class="tab-content p-3">
                               @foreach(['standar', 'formulir', 'manual_prosedur'] as $stType)
                               <div class="tab-pane {{ $stType == 'standar' ? 'active show' : '' }}" id="std-{{ $stType }}">
                                    <ul class="list-unstyled nested-sortable">
                                        @php
                                            $list = $dokumentByJenis[$stType] ?? [];
                                        @endphp

                                        @forelse($list as $dok)
                                            @include('pages.pemutu.dokumens._tree_item', ['dok' => $dok, 'level' => 0, 'collapsed' => true])
                                        @empty
                                            <li class="text-muted text-center py-3">Tidak ada dokumen {{ match($stType) { 'manual_prosedur' => 'Manual Prosedur', 'sop' => 'SOP', default => ucfirst($stType) } }}.</li>
                                        @endforelse
                                    </ul>
                               </div>
                               @endforeach
                          </div>
                     </div>
                </div>
                @endif
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
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = "{{ $activeTab }}";
        
        // --- State Management Helpers ---
        function updateUrlParam(params) {
            const url = new URL(window.location);
            Object.keys(params).forEach(key => {
                if (params[key] === null) {
                    url.searchParams.delete(key);
                } else {
                    url.searchParams.set(key);
                }
            });
            window.history.replaceState({}, '', url);
        }

        function getExpandedNodes() {
            try {
                return JSON.parse(localStorage.getItem('pemutu_tree_expanded') || '[]');
            } catch (e) { return []; }
        }

        function saveExpandedNode(id, isExpanded) {
            let expanded = getExpandedNodes();
            if (isExpanded) {
                if (!expanded.includes(id)) expanded.push(id);
            } else {
                expanded = expanded.filter(nodeId => nodeId !== id);
            }
            localStorage.setItem('pemutu_tree_expanded', JSON.stringify(expanded));
        }

        // --- Drag and Drop Logic ---
        function initializeDragAndDrop() {
            const nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));
            nestedSortables.forEach(function (el) {
                if (el.sortableInstance) el.sortableInstance.destroy();
                
                el.sortableInstance = new Sortable(el, {
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    handle: '.d-flex',
                    onEnd: function (evt) { saveHierarchy(); }
                });
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
                const activeTabPane = document.querySelector('.tab-pane.active.show');
                const rootUl = activeTabPane ? activeTabPane.querySelector('.nested-sortable') : null;
                if (!rootUl) return;
                const hierarchy = getHierarchyFromUl(rootUl);
                axios.post("{{ route('pemutu.dokumens.reorder') }}", { hierarchy: hierarchy })
                    .catch(error => console.error('Failed to save hierarchy', error));
            }
        }

        // --- UI Loader ---
        function loadDetail(url, docJenis, pushState = true) {
            let detailUrl = url;

            $('#document-detail-panel').html('<div class="card-body text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');

            axios.get(detailUrl, { params: { ajax: 1 } })
                .then(function(response) {
                    $('#document-detail-panel').html(response.data);
                    
                    if (pushState) {
                        const idMatch = url.match(/\/(\d+)$/);
                        if (idMatch) {
                            const params = new URLSearchParams(window.location.search);
                            params.set('id', idMatch[1]);
                            params.set('type', docJenis);
                            window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
                        }
                    }
                })
                .catch(function(error) {
                    $('#document-detail-panel').html('<div class="card-body text-center text-danger">Gagal memuat detail data.</div>');
                });
        }

        // --- Event Handlers ---

        // Sub-Tab Change
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            const jenis = $(this).data('jenis');
            if (jenis) {
                const params = new URLSearchParams(window.location.search);
                params.set('jenis', jenis);
                window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
            }
            setTimeout(initializeDragAndDrop, 100);
        });

        // Tree Item Click
        $(document).on('click', '.tree-item-link', function(e) {
            e.preventDefault();
            $('.tree-item-link').removeClass('fw-bold text-primary bg-blue-lt');
            $(this).addClass('fw-bold text-primary bg-blue-lt');
            loadDetail($(this).data('url'), $(this).data('jenis'));
        });

        // Toggle children
        $(document).on('click', '.tree-toggle, .tree-toggle-custom', function(e) {
             e.preventDefault();
             e.stopPropagation();
             const li = $(this).closest('li');
             const target = li.children('ul');
             const icon = $(this).find('i');
             const nodeId = li.attr('id');

             target.toggleClass('d-none');
             const isExpanded = !target.hasClass('d-none');
             
             if (isExpanded) {
                 icon.removeClass('ti-chevron-right').addClass('ti-chevron-down');
             } else {
                 icon.removeClass('ti-chevron-down').addClass('ti-chevron-right');
             }
             
             if (nodeId) saveExpandedNode(nodeId, isExpanded);
        });

        // Search Filter
        $('#tree-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.nested-sortable li').hide();
            $('.nested-sortable li').each(function() {
                var text = $(this).find('.tree-item-name').text().toLowerCase();
                if (text.indexOf(value) > -1) {
                    $(this).show().parents('li').show();
                    $(this).find('li').show();
                }
            });
        });

        // Filter Param Sync (Periode, etc.)
        $('.filter-sync-param').on('change', function() {
            const param = $(this).data('param');
            const value = $(this).val();
            const params = new URLSearchParams(window.location.search);
            
            if (value) {
                params.set(param, value);
            } else {
                params.delete(param);
            }
            
            // Also ensure 'jenis' is preserved from the active tab
            const activeSubTab = document.querySelector('.nav-link.active[data-jenis]');
            if (activeSubTab) {
                params.set('jenis', activeSubTab.dataset.jenis);
            }

            window.location.href = $(this).data('base-url') + '?' + params.toString();
        });

        // --- Initialization ---

        // 1. Initialize Drag & Drop
        initializeDragAndDrop();

        // 2. Restore Tree expansion
        const expandedNodes = getExpandedNodes();
        expandedNodes.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                const ul = el.querySelector(':scope > ul');
                const icon = el.querySelector(':scope > .d-flex .tree-toggle i, :scope > .d-flex .tree-toggle-custom i');
                if (ul) ul.classList.remove('d-none');
                if (icon) icon.classList.replace('ti-chevron-right', 'ti-chevron-down');
            }
        });

        // 3. Restore Sub-Tab from URL
        const jenisParam = urlParams.get('jenis');
        if (jenisParam) {
            const tabLink = document.querySelector(`.nav-link[data-jenis="${jenisParam}"]`);
            if (tabLink) {
                const tab = new bootstrap.Tab(tabLink);
                tab.show();
            }
        }

        // 4. Restore Selected Item and Scroll to it
        const idParam = urlParams.get('id');
        const typeParam = urlParams.get('type');
        if (idParam && typeParam) {
            const selector = typeParam === 'doksub' ? `#tree-node-sub-${idParam}` : `#tree-node-dok-${idParam}`;
            const targetNode = document.querySelector(selector);
            if (targetNode) {
                const link = targetNode.querySelector('.tree-item-link');
                if (link) {
                    link.classList.add('fw-bold', 'text-primary', 'bg-blue-lt');
                    loadDetail(link.dataset.url, link.dataset.jenis, false);
                    
                    // Expand parents
                    $(targetNode).parents('ul').removeClass('d-none').each(function() {
                        const icon = $(this).parent().find('> .d-flex .tree-toggle i, > .d-flex .tree-toggle-custom i');
                        icon.removeClass('ti-chevron-right').addClass('ti-chevron-down');
                    });

                    // Scroll into view
                    targetNode.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }
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
