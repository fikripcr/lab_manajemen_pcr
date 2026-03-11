@extends('layouts.tabler.app')

@section('title', 'Rekap Capaian Visi')

@section('header')
<x-tabler.page-header title="Rekap Capaian Visi" pretitle="Dokumen SPMI">
    <x-slot:actions>
        <div class="d-flex align-items-center gap-2">
            <x-tabler.form-select name="periode" class="mb-0" onchange="window.location.href='{{ route('pemutu.dokumen-spmi.summary') }}?periode='+this.value">
                @foreach($periods as $p)
                    <option value="{{ $p }}" {{ $selectedPeriode == $p ? 'selected' : '' }}>Periode {{ $p }}</option>
                @endforeach
            </x-tabler.form-select>
            <a href="{{ route('pemutu.dokumen.index', ['jenis' => 'visi', 'periode' => $selectedPeriode]) }}" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards mb-3">
    <!-- Left Column: Navigation Tree -->
    <div class="col-md-4">
        <x-tabler.card>
            <x-tabler.card-header title="Struktur Kebijakan" />
            
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-fill mb-0" data-bs-toggle="tabs">
                    @foreach($kebijakanTypes as $type)
                        <li class="nav-item">
                            <a href="#tab-{{ $type }}" class="nav-link {{ $activeJenis === $type ? 'active' : '' }}" data-bs-toggle="tab">
                                {{ strtoupper($type) }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($kebijakanTypes as $type)
                        @php
                            $docs = $dokumentByJenis[$type] ?? collect();
                            $hasDocs = $docs->isNotEmpty() && $docs->first() !== null;
                        @endphp
                        <div class="tab-pane {{ $activeJenis === $type ? 'active show' : '' }}" id="tab-{{ $type }}">
                            <div class="list-group list-group-flush" style="max-height: 500px; overflow-y: auto;">
                                @if($hasDocs)
                                    @foreach($docs as $doc)
                                        <button class="list-group-item list-group-item-action fw-bold bg-light load-summary" data-type="dokumen" data-id="{{ encryptId($doc->dok_id) }}">
                                            <i class="ti ti-folder me-2 text-primary"></i> 
                                            {{ $doc->judul }}
                                        </button>

                                        @if($doc->dokSubs->isEmpty())
                                            <div class="list-group-item text-muted small ps-5">
                                                <i>Belum ada poin</i>
                                            </div>
                                        @else
                                            @foreach($doc->dokSubs as $idx => $poin)
                                                <button class="list-group-item list-group-item-action ps-4 load-summary" data-type="poin" data-id="{{ encryptId($poin->doksub_id) }}">
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
                                        Belum ada dokumen {{ strtoupper($type) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-tabler.card>
    </div>

    <!-- Right Column: Detail Summary Panel -->
    <div class="col-md-8">
        <x-tabler.card class="h-100 min-vh-50">
            <div id="summary-detail-panel" class="card-body position-relative">
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
                <div id="summary-content" class="d-none"></div>
            </div>
        </x-tabler.card>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailPanel = document.getElementById('summary-content');
    const loadingState = document.querySelector('.loading-state');
    const initialState = document.querySelector('.empty-state-initial');
    const periodeSelect = document.querySelector('select[name="periode"]');
    
    // Auto-load first Visi Dokumen if it exists
    const firstDocBtn = document.querySelector('.load-summary[data-type="dokumen"]');

    document.querySelectorAll('.load-summary').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Highlight active button
            document.querySelectorAll('.load-summary').forEach(b => b.classList.remove('active', 'bg-primary-lt'));
            this.classList.add('active', 'bg-primary-lt');

            const type = this.dataset.type;
            const id = this.dataset.id;
            const periode = periodeSelect ? periodeSelect.value : '';

            // Update UI State
            initialState.classList.add('d-none');
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
        });
    });

    if(firstDocBtn) {
        firstDocBtn.click();
    }
});
</script>
@endpush
