@extends('layouts.admin.app')
@section('title', 'Organization Structure')

@section('header')
<x-tabler.page-header title="Organization Structure" pretitle="Master Data">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Add Root Unit" class="ajax-modal-btn" data-url="{{ route('pemtu.org-units.create') }}" data-modal-title="Add Root Unit" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
            <li class="nav-item">
                <a href="#tabs-list" class="nav-link active" data-bs-toggle="tab">List & Detail</a>
            </li>
            <li class="nav-item">
                <a href="#tabs-tree" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-sitemap me-2"></i> Organization Chart
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <!-- TAB 1: LIST & DETAIL -->
            <div class="tab-pane active show" id="tabs-list">
                <div class="row row-cards">
                    <!-- Tree View Sidebar -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-none">
                            <div class="card-header">
                                <h3 class="card-title">Hierarchy List</h3>
                            </div>
                            <div class="card-body overflow-auto" style="max-height: 70vh;">
                                <ul class="list-unstyled" id="org-tree">
                                    @foreach($rootUnits as $unit)
                                        @include('pages.pemtu.org-units._tree_item', ['unit' => $unit, 'level' => 0])
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Panel -->
                    <div class="col-lg-8">
                        <div id="unit-detail-panel">
                            <div class="text-center py-5">
                                <p class="text-muted">Select an organizational unit to view details.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: ORG CHART TREE (Placeholder) -->
            <div class="tab-pane" id="tabs-tree">
                <div class="empty">
                    <div class="empty-icon">
                        <i class="ti ti-sitemap"></i>
                    </div>
                    <p class="empty-title">Organization Chart</p>
                    <p class="empty-subtitle text-muted">
                        Visual chart is currently under development.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Drag and Drop Logic ---
        const nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable, #org-tree'));

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
            const rootUl = document.getElementById('org-tree');
            const hierarchy = getHierarchyFromUl(rootUl);
            
            axios.post("{{ route('pemtu.org-units.reorder') }}", { hierarchy: hierarchy })
                .then(response => console.log('Hierarchy saved'))
                .catch(error => {
                    console.error('Failed to save hierarchy', error);
                    alert('Failed to save new order. Please refresh.');
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

        // Handle Tree Item Click
        $(document).on('click', '.tree-item-link', function(e) {
            e.preventDefault();
            $('.tree-item-link').removeClass('fw-bold text-primary');
            $(this).addClass('fw-bold text-primary');
            const url = $(this).data('url');
            $('#unit-detail-panel').html('<div class="card-body text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
            axios.get(url).then(res => $('#unit-detail-panel').html(res.data)).catch(err => console.error(err));
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
    });
</script>
<style>
    .nav-tabs .nav-link.active { font-weight: bold; }
    
    .tree-item-link { cursor: pointer; text-decoration: none; color: inherit; }
    .tree-item-link:hover { color: var(--tblr-primary); font-weight: 500; }
    
    ul#org-tree, ul.nested-sortable { list-style: none; padding-left: 20px; }
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
    
    ul#org-tree > li[data-id]::before { display: none; }
    ul#org-tree > li[data-id] { padding-left: 0; }
    
    .tree-toggle { cursor: pointer; width: 20px; display: inline-block; text-align: center; color: #6e7582; }
    .tree-toggle:hover { color: var(--tblr-primary); }
    
    .sortable-ghost { opacity: 0.4; background-color: #f1f5f9; }
</style>
@endpush
