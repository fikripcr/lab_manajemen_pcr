@extends('layouts.admin.app')
@section('title', 'Organization Structure')

@section('header')
<x-tabler.page-header title="Organization Structure" pretitle="Master Data">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Add Root Unit" class="ajax-modal-btn" data-url="{{ route('pemutu.org-units.create') }}" data-modal-title="Add Root Unit" />
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
                    <i class="ti ti-settings me-2"></i> Manage Organization
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
                                        @include('pages.pemutu.org-units._tree_item', ['unit' => $unit, 'level' => 0])
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

            <!-- TAB 2: MANAGE ORGANIZATION -->
            <div class="tab-pane" id="tabs-tree">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex gap-2">
                        <x-tabler.form-select id="filter-status" name="filter_status" class="form-select-sm" style="width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </x-tabler.form-select>
                    </div>
                </div>
                <x-tabler.datatable
                    id="table-org-units"
                    route="{{ route('pemutu.org-units.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                        ['data' => 'name', 'name' => 'name', 'title' => 'Nama Unit'],
                        ['data' => 'type', 'name' => 'type', 'title' => 'Tipe'],
                        ['data' => 'parent_id', 'name' => 'parent_id', 'title' => 'Parent'],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '8%'],
                        ['data' => 'auditee', 'name' => 'auditee', 'title' => 'Auditee', 'orderable' => false, 'searchable' => false],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '10%']
                    ]"
                />
            </div>
        </div>
    </div>
</div>

<!-- Set Auditee Modal -->
<div class="modal modal-blur fade" id="modal-set-auditee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Auditee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-set-auditee">
                <div class="modal-body">
                    <input type="hidden" id="auditee-org-unit-id" name="org_unit_id">
                    <p class="text-muted mb-3">Set auditee untuk: <strong id="auditee-unit-name"></strong></p>
                    <div class="mb-3">
                        <x-tabler.form-select id="auditee-user-select" name="auditee_user_id" label="Pilih User">
                            <option value="">-- Pilih User --</option>
                        </x-tabler.form-select>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-tabler.button type="button" style="ghost-secondary" data-bs-dismiss="modal">Batal</x-tabler.button>
                    <x-tabler.button type="submit" style="primary">Simpan</x-tabler.button>
                </div>
            </form>
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
            
            axios.post("{{ route('pemutu.org-units.reorder') }}", { hierarchy: hierarchy })
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

        // Toggle Status (Manage Organization Tab)
        $(document).on('change', '.toggle-status', function() {
            const id = $(this).data('id');
            const checkbox = $(this);
            
            axios.post(`/pemutu/org-units/${id}/toggle-status`)
                .then(function(response) {
                    if (response.data.success) {
                        // Optionally show toast/notification
                        console.log('Status updated');
                    }
                })
                .catch(function(error) {
                    console.error('Failed to toggle status', error);
                    // Revert checkbox
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    alert('Gagal mengubah status. Silakan coba lagi.');
                });
        });

        // Filter by Status (Manage Organization Tab)
        $('#filter-status').on('change', function() {
            var status = $(this).val();
            var table = $('#table-org-units').DataTable();
            table.ajax.url('{{ route("pemutu.org-units.data") }}?status=' + status).load();
        });

        // Set Auditee Modal
        $(document).on('click', '.set-auditee-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            $('#auditee-org-unit-id').val(id);
            $('#auditee-unit-name').text(name);
            
            // Initialize Select2
            if (typeof window.loadSelect2 === 'function') {
                window.loadSelect2().then(() => {
                    $('#auditee-user-select').select2({
                        dropdownParent: $('#modal-set-auditee'),
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: 'Cari user...',
                        allowClear: true,
                        ajax: {
                            url: '/api/users/search',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data.map(function (user) {
                                        return {
                                            id: user.id,
                                            text: user.name
                                        };
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                    
                    // Clear previous selection
                    $('#auditee-user-select').val(null).trigger('change');
                    new bootstrap.Modal(document.getElementById('modal-set-auditee')).show();
                });
            } else {
                console.error('loadSelect2 not defined');
                alert('Gagal memuat library Select2');
            }
        });

        // Submit Set Auditee
        $('#form-set-auditee').on('submit', function(e) {
            e.preventDefault();
            const id = $('#auditee-org-unit-id').val();
            const userId = $('#auditee-user-select').val(); // Get value from Select2
            
            axios.post(`/pemutu/org-units/${id}/set-auditee`, { auditee_user_id: userId || null })
                .then(function(response) {
                    if (response.data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('modal-set-auditee')).hide();
                        $('#table-org-units').DataTable().ajax.reload(null, false);
                    }
                })
                .catch(function(error) {
                    console.error('Failed to set auditee', error);
                    alert('Gagal menyimpan auditee.');
                });
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
