@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Manajemen Halaman & Menu" pretitle="CMS">
    <x-slot:actions>
        <div class="d-flex gap-2">
            <a href="{{ route('shared.public-page.create') }}" class="btn btn-primary" id="btn-add-page">
                <i class="ti ti-plus"></i> Buat Halaman Baru
            </a>
            <x-tabler.button
                href="#"
                icon="ti ti-plus"
                class="btn-primary ajax-modal-btn d-none"
                data-url="{{ route('shared.public-menu.create') }}"
                id="btn-add-menu"
                text="Tambah Menu"
            />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tabs-page" class="nav-link active" data-bs-toggle="tab">
                            <i class="ti ti-file-text me-2"></i> Daftar Halaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-menu" class="nav-link" data-bs-toggle="tab">
                            <i class="ti ti-menu-2 me-2"></i> Struktur Menu
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- TAB 1: MANAJEMEN HALAMAN (DEFAULT) --}}
                    <div class="tab-pane active show" id="tabs-page">
                        <div class="table-responsive">
                            <x-tabler.datatable
                                id="pages-table"
                                route="{{ route('shared.public-page.index') }}"
                                :columns="[
                                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                                    ['data' => 'title', 'name' => 'title', 'title' => 'Judul'],
                                    ['data' => 'slug', 'name' => 'slug', 'title' => 'Slug'],
                                    ['data' => 'is_published', 'name' => 'is_published', 'title' => 'Status'],
                                    ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Terakhir Update'],
                                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
                                ]"
                            />
                        </div>
                    </div>

                    {{-- TAB 2: MANAJEMEN MENU --}}
                    <div class="tab-pane" id="tabs-menu">
                        @if($orgUnits->isEmpty())
                            <x-tabler.empty-state
                                title="Belum ada Menu"
                                text="Silakan tambahkan menu baru."
                                icon="ti ti-menu-2"
                            />
                        @else
                            <ul class="list-group list-group-flush sortable-list" id="menu-tree">
                                @foreach($orgUnits as $menu)
                                    @include('pages.shared.public-menu.item', ['menu' => $menu])
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('styles')
<style>
    .sortable-list {
        min-height: 10px;
    }
    .sortable-list .list-group-item {
        border: 1px solid rgba(101, 109, 119, 0.16);
        margin-bottom: 5px;
        border-radius: 4px;
        background: #fff;
    }
    .sortable-list .sortable-list {
        margin-left: 20px;
        margin-top: 5px;
        border: none;
        background: transparent;
    }
    .sortable-list .sortable-list .list-group-item {
        background: #fcfdfe;
    }
    .cursor-move {
        cursor: move;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- TAB LOGIC (PERSISTENCE & BUTTONS) ---
    const tabLinks = document.querySelectorAll('a[data-bs-toggle="tab"]');
    const btnAddMenu = document.getElementById('btn-add-menu');
    const btnAddPage = document.getElementById('btn-add-page');
    const storageKey = 'public_menu_active_tab';

    function updateButtons(href) {
        if (href === '#tabs-menu') {
            if(btnAddMenu) btnAddMenu.classList.remove('d-none');
            if(btnAddPage) btnAddPage.classList.add('d-none');
        } else {
            if(btnAddMenu) btnAddMenu.classList.add('d-none');
            if(btnAddPage) btnAddPage.classList.remove('d-none');
        }
    }

    if(tabLinks.length > 0) {
        // 1. Restore from Storage
        const savedTab = localStorage.getItem(storageKey);
        if (savedTab) {
            const triggerEl = document.querySelector(`a[href="${savedTab}"]`);
            if (triggerEl) {
                setTimeout(() => {
                    new bootstrap.Tab(triggerEl).show();
                    updateButtons(savedTab);
                }, 50); // Small delay to ensure bootstrap is ready
            }
        }

        // 2. Listen for changes
        tabLinks.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                const href = event.target.getAttribute('href');
                localStorage.setItem(storageKey, href);
                updateButtons(href);
            });
        });
    }

    // --- SORTABLE LOGIC (Menu) ---
    const nestedSortables = [].slice.call(document.querySelectorAll('.sortable-list'));

    function getHierarchyFromUl(ul) {
        const items = [];
        Array.from(ul.children).forEach(li => {
            const id = li.dataset.id;
            if (!id) return;
            
            const item = { id: id };
            const nestedUl = li.querySelector(':scope > .sortable-list');
            if (nestedUl && nestedUl.children.length > 0) {
                item.children = getHierarchyFromUl(nestedUl);
            }
            items.push(item);
        });
        return items;
    }

    function saveHierarchy() {
        const rootUl = document.getElementById('menu-tree');
        if(!rootUl) return;
        
        const hierarchy = getHierarchyFromUl(rootUl);
        
        axios.post('{{ route("shared.public-menu.reorder") }}', { hierarchy: hierarchy })
            .then(response => {
                if(typeof showSuccessMessage === 'function') {
                    showSuccessMessage('Urutan menu berhasil diperbarui');
                } else {
                    Swal.fire('Berhasil', 'Urutan menu berhasil diperbarui', 'success');
                }
            })
            .catch(error => {
                console.error('Failed to save hierarchy', error);
                if(typeof showErrorMessage === 'function') {
                    showErrorMessage('Gagal menyimpan urutan');
                } else {
                    Swal.fire('Gagal', 'Gagal menyimpan urutan', 'error');
                }
            });
    }

    nestedSortables.forEach(function (el) {
        new Sortable(el, {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            handle: '.drag-handle',
            onEnd: function (evt) {
                saveHierarchy();
            }
        });
    });
});
</script>
@endpush
