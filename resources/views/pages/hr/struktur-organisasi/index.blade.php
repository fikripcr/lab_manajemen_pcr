@extends('layouts.tabler.app')
@section('title', 'Struktur Organisasi')

@section('header')
<x-tabler.page-header title="Struktur Organisasi" pretitle="Master Data">
    <x-slot:actions>
        <x-tabler.button type="create" text="Tambah Unit" class="ajax-modal-btn" data-url="{{ route('hr.struktur-organisasi.create') }}" data-modal-title="Tambah Unit" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    @vite(['resources/js/pages/hr/struktur-organisasi.js'])
    <div class="row">
        <!-- Tree View -->
        <div class="col-lg-8">
            <x-tabler.card>
                <div class="card-header border-bottom-0">
                    <ul class="nav nav-tabs card-header-tabs mb-n1" data-bs-toggle="tabs">
                        <li class="nav-item">
                            <a href="#tabs-tree" class="nav-link active" data-bs-toggle="tab">
                                <i class="ti ti-list-details me-1"></i> Pohon Struktur
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-chart" class="nav-link" data-bs-toggle="tab" id="tab-visual-chart">
                                <i class="ti ti-chart-sitemap me-1"></i> Visual Chart
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content">
                        <!-- Tree View Tab -->
                        <div class="tab-pane active show p-3" id="tabs-tree">
                            <div class="overflow-auto" style="height: 70vh;">
                                @if($treeUnits->isEmpty())
                                    <x-tabler.empty-state
                                        title="Belum ada Unit"
                                        text="Silakan tambahkan unit organisasi baru."
                                        icon="ti ti-sitemap"
                                    />
                                @else
                                    @include('pages.hr.struktur-organisasi.tree', ['orgUnits' => $treeUnits])
                                @endif
                            </div>
                        </div>

                        <!-- Visual Chart Tab -->
                        <div class="tab-pane overflow-hidden" id="tabs-chart">
                            <div id="org-chart-container" class="position-relative" style="height: 70vh; width: 100%; background: #f4f6fa;">
                                <div id="chart-loading" class="position-absolute top-50 start-50 translate-middle">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </div>
                            </div>
                            <div class="p-2 border-top bg-light d-flex justify-content-between align-items-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" onclick="fitChart()"><i class="ti ti-maximize me-1"></i> Fit</button>
                                    <button class="btn btn-outline-secondary" onclick="zoomInChart()"><i class="ti ti-plus"></i></button>
                                    <button class="btn btn-outline-secondary" onclick="zoomOutChart()"><i class="ti ti-minus"></i></button>
                                </div>
                                <div class="small text-muted"><i class="ti ti-info-circle"></i> Gunakan mouse-wheel untuk zoom & drag untuk menggeser.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-tabler.card>
        </div>
        <!-- Detail Panel -->
        <div class="col-lg-4">
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
        // --- Shared Logic ---
        window.loadUnitDetail = function(encryptedId, url) {
            $('.tree-item-link').removeClass('fw-bold text-primary');
            $(`.tree-item-link[data-id="${encryptedId}"]`).addClass('fw-bold text-primary');

            const container = $('#detail-panel-container');
            container.html(`<x-tabler.card><x-tabler.card-body class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></x-tabler.card-body></x-tabler.card>`);

            axios.get(url)
                .then(res => container.html(res.data))
                .catch(err => {
                    console.error(err);
                    container.html(`<x-tabler.card><x-tabler.card-body class="text-danger">Gagal memuat detail.</x-tabler.card-body></x-tabler.card>`);
                });
        };

        // --- Tree View Logic (Sortable & Navigation) ---
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

            axios.post("{{ route('hr.struktur-organisasi.reorder') }}", { hierarchy: hierarchy })
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

        $(document).on('click', '.tree-item-link', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const url = $(this).data('url');
            loadUnitDetail(id, url);
        });

        // --- Visual Org Chart Logic ---
        let chartInitialized = false;

        async function initOrgChart() {
            if (chartInitialized) return;
            
            const container = document.getElementById('org-chart-container');
            if (!container) return;

            $('#chart-loading').show();

            try {
                const res = await axios.get("{{ route('hr.struktur-organisasi.chart-data') }}");
                $('#chart-loading').hide();
                
                if (typeof window.renderOrgChart === 'function') {
                    window.renderOrgChart('#org-chart-container', res.data);
                    chartInitialized = true;
                } else {
                    throw new Error('JavaScript bundle belum siap. Pastikan npm run dev sedang berjalan.');
                }
            } catch (err) {
                console.error('OrgChart Initialization Error:', err);
                $('#chart-loading').html(`<div class="text-danger p-3 text-center">
                    <i class="ti ti-alert-triangle fs-1"></i><br>
                    <strong>Gagal memuat chart:</strong><br>
                    <small>${err.message}</small>
                </div>`);
            }
        }

        $('#tab-visual-chart').on('shown.bs.tab', function() {
            initOrgChart();
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
