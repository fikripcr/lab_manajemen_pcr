@extends('layouts.tabler.app')

@section('title', 'Dokumen SPMI')

@php
    $allTabs = [
        'visi' => 'VISI', 
        'misi' => 'MISI', 
        'rjp' => 'RPJP', 
        'renstra' => 'RENSTRA', 
        'renop' => 'RENOP',
        'standar' => 'STANDAR',
        'formulir' => 'FORMULIR',
        'manual_prosedur' => 'MANUAL PROSEDUR'
    ];
    $activeJenis = request('jenis', 'visi');
@endphp

@section('header')
<x-tabler.page-header title="Dokumen SPMI" pretitle="Penetapan">
    <x-slot:actions>
        <div class="d-flex justify-content-between align-items-center gap-2">
            <x-tabler.form-select name="periode" class="mb-0 filter-sync-param" data-param="periode" data-base-url="{{ route('pemutu.dokumen.index', ['jenis' => $activeJenis]) }}">
                <option value="">Semua Periode</option>
                @foreach($periods as $p)
                    <option value="{{ $p }}" {{ request('periode') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </x-tabler.form-select>
            <div class="input-icon">
                <span class="input-icon-addon">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" name="search" class="form-control filter-sync" data-target="#document-tree" placeholder="Cari dokumen..." value="{{ request('search') }}">
            </div>
            @if(in_array($activeJenis, ['standar', 'formulir', 'manual_prosedur']))
                <x-tabler.button type="create" text="{{ $allTabs[$activeJenis] }}" class="btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'tabs' => in_array($activeJenis, ['standar', 'formulir', 'manual_prosedur']) ? 'standar' : 'kebijakan']) }}" data-modal-title="Dokumen {{ $allTabs[$activeJenis] }}" />
            @endif
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <!-- Tree View Sidebar -->
    <div class="col-lg-5">
        <x-tabler.card> 
            <div class="card-body border-bottom bg-transparent p-0">
                <div class="p-2 border-bottom">
                    <ul class="nav nav-pills nav-fill gap-1 px-2">
                        @foreach(array_slice($allTabs, 0, 5, true) as $key => $label)
                        <li class="nav-item">
                            <a href="{{ route('pemutu.dokumen.index', ['jenis' => $key, 'periode' => $selectedPeriode]) }}" 
                               class="nav-link py-1 px-2 small {{ $activeJenis == $key ? 'active shadow-sm' : '' }}">
                                {{ $label }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="p-2">
                    <ul class="nav nav-pills nav-fill gap-1 px-2">
                        @foreach(array_slice($allTabs, 5, null, true) as $key => $label)
                        <li class="nav-item">
                            <a href="{{ route('pemutu.dokumen.index', ['jenis' => $key, 'periode' => $selectedPeriode]) }}" 
                               class="nav-link py-1 px-2 small {{ $activeJenis == $key ? 'active shadow-sm' : '' }}">
                                {{ $label }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="card-body p-0 overflow-auto" id="document-tree" style="max-height: 65vh;">
                <div class="p-3">
                    @php
                        $isTreeBased = in_array($activeJenis, ['standar', 'formulir', 'manual_prosedur']);
                        $dokData = $dokumentByJenis[$activeJenis] ?? null;
                    @endphp

                    @if($isTreeBased)
                        {{-- Tree view for Standar, Formulir, Manual Prosedur --}}
                        <ul class="list-unstyled nested-sortable mb-0">
                            @forelse($dokData ?? [] as $dok)
                                @include('pages.pemutu.dokumen._tree_item', ['dok' => $dok, 'level' => 0, 'collapsed' => true])
                            @empty
                                <li class="text-muted text-center py-3">Tidak ada dokumen {{ $allTabs[$activeJenis] }}.</li>
                            @endforelse
                        </ul>
                    @else
                        {{-- Specialized view for Visi, Misi, RPJP, etc --}}
                        @php $dok = $dokData->first(); @endphp
                        @if($dok)
                            <div class="mb-3">
                                <div class="tree-node-row rounded">
                                    <a href="#" class="tree-item-link w-100 d-flex align-items-center text-decoration-none px-2 py-1"
                                       data-url="{{ route('pemutu.dokumen-spmi.show', ['type' => 'dokumen', 'id' => $dok->encrypted_dok_id]) }}"
                                       data-jenis="{{ $dok->jenis }}">
                                        <span class="avatar avatar-sm rounded bg-primary text-white me-3">
                                            {{ substr($dok->judul, 0, 1) }}
                                        </span>
                                        <div>
                                            <div class="fw-bold text-reset">{{ $dok->judul }}</div>
                                            <div class="text-muted small">
                                                <i class="ti ti-file-description me-1"></i> Dokument Induk &bull; {{ $dok->dokSubs->count() }} poin
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="ms-2">
                                <h5 class="text-muted mb-2 ms-2"><i class="ti ti-list me-2"></i>Daftar Poin {{ $allTabs[$activeJenis] }}:</h5>
                                <ul class="list-unstyled mb-0 ms-2 border-start ms-3 ps-3">
                                    @forelse($dok->dokSubs as $sub)
                                    <li class="mb-1" id="tree-node-sub-{{ $sub->encrypted_doksub_id }}">
                                        <div class="tree-node-row rounded">
                                            <a href="#" class="tree-item-link w-100 d-flex align-items-center text-decoration-none px-2 py-1"
                                               data-url="{{ route('pemutu.dokumen-spmi.show', ['type' => 'poin', 'id' => $sub->encrypted_doksub_id]) }}"
                                               data-jenis="doksub">
                                                <span class="avatar avatar-xs rounded bg-secondary-lt me-2 flex-shrink-0">{{ $sub->seq ?? substr($sub->judul, 0, 1) }}</span>
                                                <div class="text-truncate">
                                                    <span class="tree-item-name text-reset">{{ $sub->judul }}</span>
                                                    @if($sub->kode)
                                                    <div class="text-muted small"><i class="ti ti-tag me-1"></i>{{ $sub->kode }}</div>
                                                    @endif
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    @empty
                                    <li class="text-muted text-center py-3">Belum ada poin {{ $allTabs[$activeJenis] }}.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @else
                            <div class="text-muted text-center py-3">Dokumen {{ $allTabs[$activeJenis] }} belum dibuat.</div>
                        @endif
                    @endif
                </div>
            </div>
        </x-tabler.card>
    </div>

    <!-- Detail Panel -->
    <div class="col-lg-7">
        <div id="document-detail-panel">
            <x-tabler.card>
                <x-tabler.card-body class="text-center py-5">
                    <p class="text-muted">Pilih dokumen untuk melihat detail.</p>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    if (typeof window.initPemutuWorkspace === 'function') {
        window.initPemutuWorkspace({
            reorderUrl: '{{ route("pemutu.dokumen.reorder") }}'
        });
    }

    $(document).ready(async function() {
        // Load FilePond if available on the page
        if (typeof window.loadFilePond === 'function') {
            await window.loadFilePond();
        }

        // Handle visual selection class for Kebijakan specific tree-node-rows
        $(document).on('click', '.tree-item-link', function() {
            $('.tree-node-row').removeClass('bg-primary-lt');
            $(this).closest('.tree-node-row').addClass('bg-primary-lt');
        });

        @if($activeTab === 'kebijakan')
            // Auto-click the Dokumen Induk link when switching Kebijakan tabs
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                let targetId = $(e.target).attr('href'); // e.g. #tab-visi
                if (targetId && targetId.startsWith('#tab-')) {
                    $(targetId).find('.tree-item-link').first().trigger('click');
                }
            });

            // On initial load, auto-click the active tab's first link only if no specific ID is requested
            setTimeout(function() {
                const urlParams = new URL(window.location.href).searchParams;
                if (!urlParams.get('id')) {
                    $('.tab-pane.active .tree-item-link').first().trigger('click');
                }
            }, 300);
        @endif
    });
</script>
@endpush
