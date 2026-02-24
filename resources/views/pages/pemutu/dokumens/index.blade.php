@extends('layouts.tabler.app')

@section('title', 'Dokumen SPMI')

@section('header')
<x-tabler.page-header title="Dokumen SPMI" pretitle="Berkas">
    <x-slot:actions>
        <div class="d-flex justify-content-between align-items-center gap-2">
            <x-tabler.form-select name="periode" class="mb-0 filter-sync-param" data-param="periode" data-base-url="{{ route('pemutu.dokumens.index') }}">
                <option value="">Semua Periode</option>
                @foreach($periods as $p)
                    <option value="{{ $p }}" {{ request('periode') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </x-tabler.form-select>
            <div class="input-icon">
                <span class="input-icon-addon">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" id="tree-search" class="form-control" placeholder="Cari dokumen...">
            </div>
            @if($activeTab === 'standar')
                <x-tabler.button type="create" text="Tambah Standar" class="ajax-modal-btn" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'tabs' => 'standar']) }}" data-modal-title="Tambah Dokumen Standar" />
            @else
                <x-tabler.button type="create"  text="Tambah Kebijakan" class="ajax-modal-btn" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'tabs' => $activeTab]) }}" data-modal-title="Tambah Dokumen Kebijakan" />
            @endif
        </div>
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
                        <a href="{{ route('pemutu.dokumens.index', ['tabs' => 'kebijakan', 'periode' => request('periode')]) }}" class="nav-link {{ $activeTab === 'kebijakan' ? 'active' : '' }}"><i class="ti ti-gavel me-2"></i>Kebijakan</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pemutu.dokumens.index', ['tabs' => 'standar', 'periode' => request('periode')]) }}" class="nav-link {{ $activeTab === 'standar' ? 'active' : '' }}"><i class="ti ti-certificate me-2"></i>Standar</a>
                    </li>
                </ul>
                <div class="card-actions">
                     <x-tabler.button href="{{ route('pemutu.dokumens.index', ['tabs' => $activeTab]) }}" style="ghost-secondary" size="xs" icon="ti ti-refresh" title="Reset Filters" icon-only />
                </div>
            </div>

            <div class="tab-content">
                @if($activeTab === 'kebijakan')
                <!-- PANEL KEBIJAKAN -->
                <div class="tab-pane active show" id="main-kebijakan">
                    <!-- Filters & Sub-Tabs -->
                    <div class="card-body border-bottom bg-transparent py-3">
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
                     <div class="card-body border-bottom bg-transparent py-3">
                         <ul class="nav nav-pills nav-fill" data-bs-toggle="tabs">
                            <li class="nav-item"><a href="#std-standar" class="nav-link py-1 {{ (!request('jenis') && $activeTab === 'standar') || request('jenis') == 'standar' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="standar">Standar</a></li>
                            <li class="nav-item"><a href="#std-formulir" class="nav-link py-1 {{ request('jenis') == 'formulir' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="formulir">Formulir</a></li>
                            <li class="nav-item"><a href="#std-manual_prosedur" class="nav-link py-1 {{ request('jenis') == 'manual_prosedur' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="manual_prosedur">Manual Prosedur</a></li>
                         </ul>
                     </div>
                     <div class="card-body p-0 overflow-auto" style="max-height: 55vh;">
                           <div class="tab-content p-3">
                               @foreach(['standar', 'formulir', 'manual_prosedur'] as $stType)
                               @php
                                   $isInitialActive = (!request('jenis') && $stType === 'standar') || request('jenis') == $stType;
                               @endphp
                               <div class="tab-pane {{ $isInitialActive ? 'active show' : '' }}" id="std-{{ $stType }}">
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
        <div id="document-detail-panel">
            <div class="card">
                <div class="card-body text-center py-5">
                    <p class="text-muted">Pilih dokumen untuk melihat detail.</p>
                </div>
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

            $('#document-detail-panel').html('<div class="card"><div class="card-body text-center py-5"><div class="spinner-border text-primary" role="status"></div></div></div>');

            axios.get(detailUrl, { 
                params: { ajax: 1 },
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function(response) {
                    $('#document-detail-panel').html(response.data);
                    
                    if (pushState) {
                        const idMatch = url.match(/\/([a-zA-Z0-9]+)$/);
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

        // --- Event Handlers & Refresh Logic ---

        function refreshUI() {
            const url = new URL(window.location);
            url.searchParams.set('ajax', '1');

            // Show global progress indicator if needed? For now just silent refresh
            axios.get(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(response => {
                const $temp = $('<div>').html(response.data);
                const $newTabContent = $temp.find('.tab-content').first();
                if ($newTabContent.length) {
                    $('.tab-content').first().html($newTabContent.html());
                    
                    // Re-init Sortable and Tree functionality
                    initializeDragAndDrop();
                    
                    // Re-expand previously expanded nodes
                    const expanded = getExpandedNodes();
                    expanded.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) {
                            const ul = el.querySelector(':scope > ul');
                            const icon = el.querySelector(':scope > .d-flex .tree-toggle i, :scope > .d-flex .tree-toggle-custom i');
                            if (ul) ul.classList.remove('d-none');
                            if (icon) icon.classList.replace('ti-chevron-right', 'ti-chevron-down');
                        }
                    });

                    // Highlight selected item and reload its detail panel
                    const params = new URLSearchParams(window.location.search);
                    const idParam = params.get('id');
                    const typeParam = params.get('type');
                    if (idParam && typeParam) {
                        const selector = typeParam === 'doksub' ? `#tree-node-sub-${idParam}` : `#tree-node-dok-${idParam}`;
                        const targetNode = document.querySelector(selector);
                        if (targetNode) {
                            const row = targetNode.querySelector('.tree-node-row');
                            if (row) {
                                row.classList.add('fw-bold', 'text-primary', 'bg-blue-lt');
                                const link = row.querySelector('.tree-item-link');
                                if (link) loadDetail(link.dataset.url, link.dataset.jenis, false);
                            }
                        }
                    }
                }
            }).catch(err => {
                console.error('Failed to refresh UI', err);
            });
        }

        // Global listeners for AJAX form/delete success
        $(document).off('ajax-form:success.refreshUI').on('ajax-form:success.refreshUI', '.ajax-form', function() {
            refreshUI(); 
        });
        
        $(document).off('ajax-delete:success.refreshUI').on('ajax-delete:success.refreshUI', '.ajax-delete', function() {
            refreshUI(); 
        });

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
        $(document).on('click', '.tree-node-row', function(e) {
            if ($(e.target).closest('.tree-toggle, .tree-toggle-custom').length) return;
            
            e.preventDefault();
            $('.tree-node-row').removeClass('fw-bold text-primary bg-blue-lt');
            $(this).addClass('fw-bold text-primary bg-blue-lt');
            
            const link = $(this).find('.tree-item-link');
            if (link.length) {
                loadDetail(link.data('url'), link.data('jenis'));
            }
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
    .tree-node-row { cursor: pointer; padding: 2px 8px; border-radius: 4px; transition: background-color 0.1s; width: 100%; }
    .tree-node-row:hover { background-color: rgba(var(--tblr-primary-rgb), 0.08); }
    .tree-node-row:hover .tree-item-link, 
    .tree-node-row:hover .tree-toggle, 
    .tree-node-row:hover .tree-item-name { color: var(--tblr-primary) !important; }

    .tree-item-link { text-decoration: none; color: inherit; display: block; }

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
