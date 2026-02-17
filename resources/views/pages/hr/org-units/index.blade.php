@extends('layouts.admin.app')
@section('title', 'Struktur Organisasi')

@section('header')
<x-tabler.page-header title="Struktur Organisasi" pretitle="Master Data HR">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Unit" class="ajax-modal-btn" data-url="{{ route('hr.org-units.create') }}" data-modal-title="Tambah Unit" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
            <li class="nav-item">
                <a href="#tabs-tree" class="nav-link active" data-bs-toggle="tab">
                    <i class="ti ti-hierarchy-2 me-2"></i> Hierarki
                </a>
            </li>
            <li class="nav-item">
                <a href="#tabs-manage" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-settings me-2"></i> Manage
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <!-- TAB 1: HIERARCHY TREE -->
            <div class="tab-pane active show" id="tabs-tree">
                <div class="row row-cards">
                    <!-- Tree View -->
                    <div class="col-lg-5">
                        <div class="alert alert-info mb-3">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Info:</strong> Struktur organisasi ini menggantikan menu Departemen, Prodi, Posisi, dan Jabatan Struktural.
                        </div>
                        <div class="card border-0 shadow-none">
                            <div class="card-header">
                                <h3 class="card-title">Struktur Organisasi</h3>
                            </div>
                            <div class="card-body overflow-auto" style="max-height: 70vh;">
                                <ul class="list-unstyled" id="org-tree">
                                    @foreach($rootUnits as $unit)
                                        @include('pages.hr.org-units._tree_item', ['unit' => $unit, 'level' => 0])
                                    @endforeach
                                    @if($rootUnits->isEmpty())
                                        <li class="text-muted">Belum ada unit organisasi.</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Legend / Detail Panel -->
                    <div class="col-lg-7">
                        <div id="detail-panel-container">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Legenda Tipe Unit</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($types as $key => $label)
                                        <div class="col-md-4 mb-2">
                                            <span class="badge bg-secondary-lt">{{ $label }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    <hr>
                                    <p class="text-muted mb-0">
                                        <small>Klik pada unit di panel kiri untuk melihat detail.</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: MANAGE (DATATABLE) -->
            <div class="tab-pane" id="tabs-manage">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex gap-2">
                        <x-tabler.form-select id="filter-type" name="filter-type" label="Filter Tipe" class="form-select-sm mb-0" style="width: 180px;">
                            <option value="">Semua Tipe</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </x-tabler.form-select>
                        <x-tabler.form-select id="filter-status" name="filter-status" label="Filter Status" class="form-select-sm mb-0" style="width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </x-tabler.form-select>
                    </div>
                </div>
                <x-tabler.datatable
                    id="table-org-units"
                    route="{{ route('hr.org-units.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                        ['data' => 'name', 'name' => 'name', 'title' => 'Nama Unit'],
                        ['data' => 'parent_id', 'name' => 'parent_id', 'title' => 'Parent'],
                        ['data' => 'level', 'name' => 'level', 'title' => 'Level', 'width' => '8%', 'class' => 'text-center'],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '8%'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '10%']
                    ]"
                />
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
                const nestedUl = li.querySelector(':scope > ul.nested-sortable');
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
            
            axios.post("{{ route('hr.org-units.reorder') }}", { hierarchy: hierarchy })
                .then(response => console.log('Hierarchy saved'))
                .catch(error => {
                    console.error('Failed to save hierarchy', error);
                    alert('Gagal menyimpan urutan. Silakan refresh.');
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

        // Toggle Status (Manage Tab)
        $(document).on('change', '.toggle-status', function() {
            const id = $(this).data('id');
            const checkbox = $(this);
            
            axios.post(`/hr/org-units/${id}/toggle-status`)
                .then(function(response) {
                    if (response.data.success) {
                        console.log('Status updated');
                    }
                })
                .catch(function(error) {
                    console.error('Failed to toggle status', error);
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    alert('Gagal mengubah status.');
                });
        });

        // Tree Item Click - Load Detail
        $(document).on('click', '.tree-item-link', function(e) {
            e.preventDefault();
            $('.tree-item-link').removeClass('fw-bold text-primary');
            $(this).addClass('fw-bold text-primary');
            
            const url = $(this).data('url');
            const container = $('#detail-panel-container');
            container.html('<div class="card"><div class="card-body text-center py-5"><div class="spinner-border text-primary" role="status"></div></div></div>');
            
            axios.get(url)
                .then(res => container.html(res.data))
                .catch(err => {
                    console.error(err);
                    container.html('<div class="card"><div class="card-body text-danger">Gagal memuat detail.</div></div>');
                });
        });

        // Filter by Type
        $('#filter-type, #filter-status').on('change', function() {
            var type = $('#filter-type').val();
            var status = $('#filter-status').val();
            var table = $('#table-org-units').DataTable();
            var url = '{{ route("hr.org-units.data") }}?type=' + type + '&status=' + status;
            table.ajax.url(url).load();
        });
    });
</script>
<style>
    .nav-tabs .nav-link.active { font-weight: bold; }
    
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
</style>
@endpush
