@extends('layouts.tabler.app')

@section('title', 'Pilih Layanan - E-Office')

@section('header')
<x-tabler.page-header title="Pilih Jenis Layanan" pretitle="Layanan Digital E-Office">
    <x-slot:actions>
        @if(auth()->user()->hasRole('admin'))
            <x-tabler.button href="{{ route('eoffice.master-data.index') }}" icon="ti ti-database" text="Master Data" />
        @endif
        <x-tabler.button href="{{ route('eoffice.layanan.index') }}" icon="ti ti-list" text="Lihat Pengajuan Saya" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    {{-- Search & Filter Bar --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" id="serviceSearch" class="form-control" placeholder="Cari layanan...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-select id="categoryFilter" name="categoryFilter">
                            <option value="">Semua Kategori</option>
                            @foreach($grouped as $category => $items)
                                <option value="{{ strtolower($category) }}">{{ strtoupper($category) }}</option>
                            @endforeach
                        </x-tabler.form-select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Services Grid --}}
    @forelse($grouped as $category => $items)
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <h2 class="page-title mb-0 me-3">
                    <i class="ti ti-folder me-2 text-primary"></i>{{ strtoupper($category) }}
                </h2>
                <span class="badge bg-primary-lt text-primary">{{ count($items) }} Layanan</span>
            </div>
        </div>

        @foreach($items as $item)
            <div class="col-md-6 col-lg-4 service-card" data-category="{{ strtolower($category) }}" data-name="{{ strtolower($item->nama_layanan) }}">
                <div class="card card-stacked card-service h-100">
                    <div class="card-body d-flex flex-column">
                        {{-- Card Header with Icon --}}
                        <div class="d-flex align-items-start mb-3">
                            <div class="service-icon-wrapper me-3">
                                <span class="avatar avatar-lg rounded-circle bg-gradient-primary">
                                    <i class="ti {{ $item->icon ?? 'ti-file-description' }} fs-2"></i>
                                </span>
                            </div>
                            <div class="flex-fill">
                                <h3 class="card-title mb-1 fs-4">{{ $item->nama_layanan }}</h3>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary-lt text-primary">
                                        <i class="ti ti-clock me-1"></i>{{ $item->batas_pengerjaan }} Jam
                                    </span>
                                    @if($item->is_active)
                                        <span class="badge bg-green-lt text-green">
                                            <i class="ti ti-check me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-red-lt text-red">
                                            <i class="ti ti-x me-1"></i>Non-Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Service Description --}}
                        @if($item->deskripsi)
                            <p class="text-muted small flex-grow-1 mb-3">
                                {{ Str::limit($item->deskripsi, 100) }}
                            </p>
                        @endif

                        {{-- Requirements Info --}}
                        <div class="service-info mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-file-text text-muted me-2"></i>
                                <span class="text-muted small">
                                    {{ $item->isians->count() ?? 0 }} Field Isian
                                </span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-users text-muted me-2"></i>
                                <span class="text-muted small">
                                    {{ $item->pics->count() ?? 0 }} PIC
                                </span>
                            </div>
                            @if($item->only_show_on && is_array($item->only_show_on))
                                <div class="d-flex align-items-center flex-wrap gap-1">
                                    <i class="ti ti-shield text-muted me-2"></i>
                                    <span class="text-muted small me-1">Role:</span>
                                    @foreach(array_slice($item->only_show_on, 0, 3) as $role)
                                        <span class="badge badge-outline text-blue">{{ $role }}</span>
                                    @endforeach
                                    @if(count($item->only_show_on) > 3)
                                        <span class="badge badge-outline text-muted">+{{ count($item->only_show_on) - 3 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Progress/Stats --}}
                        <div class="service-stats mt-auto">
                            <div class="row text-center g-2">
                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <div class="text-muted small">Total Pengajuan</div>
                                        <div class="fs-4 fw-bold text-primary">{{ $item->layanans_count ?? 0 }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <div class="text-muted small">Selesai</div>
                                        <div class="fs-4 fw-bold text-success">{{ $item->completed_count ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Footer with Action --}}
                    <div class="card-footer bg-transparent border-0 pb-3 px-3">
                        @if($item->is_active)
                            <x-tabler.button 
                                href="{{ route('eoffice.layanan.create', $item->encrypted_jenislayanan_id) }}" 
                                class="btn-primary w-100" 
                                icon="ti ti-arrow-right" 
                                text="Ajukan Layanan" 
                            />
                        @else
                            <button class="btn w-100 btn-secondary" disabled>
                                <i class="ti ti-lock me-1"></i>Layanan Tidak Aktif
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="empty">
                        <div class="empty-icon text-muted">
                            <i class="ti ti-mood-empty fs-1"></i>
                        </div>
                        <p class="empty-title h3">Belum ada layanan aktif</p>
                        <p class="empty-subtitle text-muted mb-4">
                            Silakan hubungi admin untuk informasi lebih lanjut.
                        </p>
                        <x-tabler.button href="{{ route('eoffice.dashboard') }}" icon="ti ti-arrow-left" text="Kembali ke Dashboard" />
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>

{{-- Quick Help Card --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card bg-primary-lt border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-2">
                            <i class="ti ti-help-circle me-2"></i>Butuh Bantuan?
                        </h4>
                        <p class="text-muted mb-0">
                            Jika Anda mengalami kesulitan dalam pengajuan layanan, silakan hubungi tim support atau lihat panduan penggunaan.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <x-tabler.button href="{{ route('eoffice.feedback.index') }}" class="btn-primary" icon="ti ti-message" text="Hubungi Support" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-service {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card-service:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--tblr-primary) 0%, var(--tblr-primary-dark) 100%);
    }
    
    .service-icon-wrapper {
        flex-shrink: 0;
    }
    
    .service-info {
        background: var(--tblr-bg-surface-secondary);
        border-radius: var(--tblr-border-radius);
        padding: 0.75rem;
    }
    
    .service-stats .bg-light {
        background: var(--tblr-bg-surface-secondary) !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('serviceSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const serviceCards = document.querySelectorAll('.service-card');

    function filterServices() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value.toLowerCase();

        serviceCards.forEach(card => {
            const cardName = card.getAttribute('data-name');
            const cardCategory = card.getAttribute('data-category');
            
            const matchesSearch = !searchTerm || cardName.includes(searchTerm);
            const matchesCategory = !selectedCategory || cardCategory === selectedCategory;
            
            if (matchesSearch && matchesCategory) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        // Hide category headers if no items visible
        document.querySelectorAll('.col-12 .page-title').forEach(header => {
            const categorySection = header.closest('.col-12');
            const cardsInCategory = categorySection.parentElement.querySelectorAll('.service-card[style=""]');
            
            // Check if any card in this category is visible
            let hasVisibleCards = false;
            let nextElement = categorySection.nextElementSibling;
            while (nextElement && !nextElement.classList.contains('col-12')) {
                if (nextElement.classList.contains('service-card') && nextElement.style.display !== 'none') {
                    hasVisibleCards = true;
                    break;
                }
                nextElement = nextElement.nextElementSibling;
            }
        });
    }

    searchInput.addEventListener('input', filterServices);
    categoryFilter.addEventListener('change', filterServices);
});
</script>
@endpush
