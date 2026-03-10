@extends('layouts.tabler.app')
@section('title', 'Struktur Organisasi (Shared)')

@section('header')
<x-tabler.page-header title="Struktur Organisasi" pretitle="Shared Master Data">
    <x-slot:actions>
        <x-tabler.button type="create" text="Tambah Unit" class="ajax-modal-btn" data-url="{{ route('shared.struktur-organisasi.create') }}" data-modal-title="Tambah Unit" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

        <div class="row">
            <!-- Tree View -->
            <div class="col-lg-7">
                <x-tabler.card>
                    <x-tabler.card-body>
                <div class="overflow-auto" style="max-height: 75vh;">
                    @if($treeUnits->isEmpty())
                        <x-tabler.empty-state
                            title="Belum ada Unit"
                            text="Silakan tambahkan unit organisasi baru."
                            icon="ti ti-sitemap"
                        />
                    @else
                        @include('pages.shared.struktur-organisasi.tree', ['orgUnits' => $treeUnits])
                    @endif
                </div>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>
            <!-- Detail Panel -->
            <div class="col-lg-5">
                <div id="detail-panel-container">
                    <x-tabler.card class="shadow-none border">
                        <x-tabler.card-body class="py-5 text-center">
                            <div class="text-center py-5">
                                <i class="ti ti-click fs-1 text-muted opacity-25"></i>
                                <p class="text-muted mt-2">Pilih unit di sebelah kiri untuk melihat detail informasi.</p>
                            </div>

                            <hr class="my-3">
                            <div class="text-uppercase text-muted small fw-bold mb-3">Legenda Tipe</div>
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                @foreach($types as $key => $label)
                                    <span class="badge bg-secondary-lt">{{ $label }}</span>
                                @endforeach
                            </div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>
            </div>
        </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Drag and Drop Logic --- (unchanged)
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

            axios.post("{{ route('shared.struktur-organisasi.reorder') }}", { hierarchy: hierarchy })
                .then(response => console.log('Hierarchy saved'))
                .catch(error => {
                    console.error('Failed to save hierarchy', error);
                    alert('Gagal menyimpan urutan. Silakan refresh.');
                });
        }

        if (nestedSortables.length > 0) {
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
        }

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

        // Tree Item Click - Load Detail
        $(document).on('click', '.tree-item-link', function(e) {
            e.preventDefault();
            $('.tree-item-link').removeClass('fw-bold text-primary');
            $(this).addClass('fw-bold text-primary');

            const url = $(this).data('url');
            const container = $('#detail-panel-container');
            container.html(`<x-tabler.card><x-tabler.card-body class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></x-tabler.card-body></x-tabler.card>`);

            axios.get(url)
                .then(res => container.html(res.data))
                .catch(err => {
                    console.error(err);
                    container.html(`<x-tabler.card><x-tabler.card-body class="text-danger">Gagal memuat detail.</x-tabler.card-body></x-tabler.card>`);
                });
        });
    });
</script>
<style>

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
