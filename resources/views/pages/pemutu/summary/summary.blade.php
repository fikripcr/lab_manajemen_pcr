@extends('layouts.tabler.app')

@section('title', 'Rekap Capaian')

@section('header')
<x-tabler.page-header title="Rekap Capaian" pretitle="Summary Penetapan Siklus {{ $siklus['tahun'] }}">
    <x-slot:actions>
        <x-pemutu.kelompok-selector :active-kelompok="$activeKelompok" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="tab-pane active show">
    @if(!$activePeriode)
        <x-tabler.card>
            <x-tabler.card-body class="py-5 text-center">
                <x-tabler.empty-state 
                    title="Periode Belum Tersedia" 
                    text="Data periode {{ $kelompokLabel }} untuk tahun {{ $siklus['tahun'] }} belum dibuat."
                    icon="ti ti-calendar-off" 
                />
            </x-tabler.card-body>
        </x-tabler.card>
    @else
        <div class="row row-cards mb-3">
            <!-- Left Column: Navigation Tree -->
            <div class="col-md-4">
                <x-tabler.card>
                    <x-tabler.card-header title="Struktur Kebijakan - {{ $kelompokLabel }}" />
                    
                    <x-tabler.card-body class="p-0">
                        <ul class="nav nav-tabs nav-fill mb-0" id="summary-kebijakan-tabs" data-bs-toggle="tabs" role="tablist">
                            @foreach($kebijakanTypes as $kType)
                                <li class="nav-item">
                                    <a href="#tab-{{ $kType }}" class="nav-link {{ $activeJenis === $kType ? 'active' : '' }}" data-bs-toggle="tab">
                                        {{ strtoupper($kType) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach($kebijakanTypes as $kType)
                                @php
                                    $docs = $dokumentByJenis[$kType] ?? collect();
                                    $hasDocs = $docs->isNotEmpty() && $docs->first() !== null;
                                @endphp
                                <div class="tab-pane {{ $activeJenis === $kType ? 'active show' : '' }}" id="tab-{{ $kType }}">
                                    <div class="list-group list-group-flush" style="max-height: 500px; overflow-y: auto;">
                                        @if($hasDocs)
                                            @foreach($docs as $doc)
                                                <button class="list-group-item list-group-item-action fw-bold bg-light load-summary" 
                                                        data-type="dokumen" 
                                                        data-id="{{ encryptId($doc->dok_id) }}"
                                                        data-periode="{{ $activePeriode->periode }}"
                                                        data-pane="#summary-detail-panel">
                                                    <i class="ti ti-folder me-2 text-primary"></i> 
                                                    {{ $doc->judul }}
                                                </button>

                                                @if($doc->dokSubs->isEmpty())
                                                    <div class="list-group-item text-muted small ps-5">
                                                        <i>Belum ada poin</i>
                                                    </div>
                                                @else
                                                    @foreach($doc->dokSubs as $idx => $poin)
                                                        <button class="list-group-item list-group-item-action ps-4 load-summary" 
                                                                data-type="poin" 
                                                                data-id="{{ encryptId($poin->doksub_id) }}"
                                                                data-periode="{{ $activePeriode->periode }}"
                                                                data-pane="#summary-detail-panel">
                                                            <div class="d-flex w-100 align-items-center">
                                                                <span class="avatar avatar-xs bg-secondary-lt me-2">{{ $idx + 1 }}</span>
                                                                <div class="text-truncate">
                                                                    @if($poin->kode) <span class="badge bg-secondary-lt me-1">{{ $poin->kode }}</span> @endif
                                                                    {{ $poin->judul }}
                                                                </div>
                                                            </div>
                                                        </button>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="list-group-item text-center text-muted p-4">
                                                <i class="ti ti-file-off fs-2 mb-2 d-block opacity-50"></i>
                                                Belum ada dokumen {{ strtoupper($kType) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>

            <!-- Right Column: Detail Summary Panel -->
            <div class="col-md-8">
                <x-tabler.card class="h-100 min-vh-50">
                    <x-tabler.card-body id="summary-detail-panel" class="position-relative summary-detail-panel">
                        <!-- Initial Empty State -->
                        <div class="empty empty-state-initial">
                            <div class="empty-icon">
                                <i class="ti ti-arrow-left mt-2" style="font-size: 3rem;"></i>
                            </div>
                            <p class="empty-title">Pilih Dokumen atau Poin</p>
                            <p class="empty-subtitle text-muted">
                                Pilih dokumen atau spesifik poin pada panel di sebelah kiri untuk melihat rekap capaian AMI pada seluruh struktur turunannya.
                            </p>
                        </div>
                        
                        <!-- Loading State (hidden by default) -->
                        <div class="loading-state d-none text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <div class="mt-3 text-muted">Menghitung Capaian Terpadu...</div>
                        </div>

                        <!-- Content Container (hidden by default) -->
                        <div class="summary-content d-none"></div>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function loadSummary(btn) {
        const type = btn.dataset.type;
        const id = btn.dataset.id;
        const periode = btn.dataset.periode;
        const paneSelector = btn.dataset.pane;
        
        const pane = document.querySelector(paneSelector);
        if (!pane) return;

        const detailPanel = pane.querySelector('.summary-content');
        const loadingState = pane.querySelector('.loading-state');
        const initialState = pane.querySelector('.empty-state-initial');

        // Highlight active button within current tab
        btn.closest('.list-group').querySelectorAll('.load-summary').forEach(b => b.classList.remove('active', 'bg-primary-lt'));
        btn.classList.add('active', 'bg-primary-lt');

        // Update UI State
        if(initialState) initialState.classList.add('d-none');
        detailPanel.classList.add('d-none');
        loadingState.classList.remove('d-none');

        // Fetch AJAX Data
        fetch(`{{ url('pemutu/dokumen-spmi/summary') }}/${type}/${id}?periode=${periode}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if(!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            detailPanel.innerHTML = html;
            loadingState.classList.add('d-none');
            detailPanel.classList.remove('d-none');
        })
        .catch(error => {
            console.error('Error fetching summary:', error);
            loadingState.classList.add('d-none');
            detailPanel.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <div class="d-flex">
                        <div><i class="ti ti-alert-circle me-2"></i></div>
                        <div>Terjadi kesalahan saat memuat data rekap. Silakan coba lagi.</div>
                    </div>
                </div>
            `;
            detailPanel.classList.remove('d-none');
        });
    }

    $(document).on('click', '.load-summary', function(e) {
        e.preventDefault();
        loadSummary(this);
    });

    // Initial auto-click for active tab
    setTimeout(function() {
        const firstBtn = document.querySelector('.load-summary');
        if (firstBtn) firstBtn.click();
    }, 300);
});
</script>
@endpush
