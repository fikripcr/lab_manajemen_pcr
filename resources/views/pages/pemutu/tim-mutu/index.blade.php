@extends('layouts.tabler.app')
@section('title', 'Tim Mutu - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Tim Mutu" pretitle="Siklus SPMI {{ $siklus['tahun'] }}">
</x-tabler.page-header>
@endsection

@section('content')
<div class="mb-3">
    <x-tabler.card>
        <x-tabler.card-body class="p-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" id="search-unit" class="form-control" placeholder="Cari Unit...">
                    </div>
                </div>
            </div>
        </x-tabler.card-body>
    </x-tabler.card>
</div>

<div class="row row-cards unit-cards">
    @forelse($units as $unit)
        @php
            $unitId = encryptId($unit->orgunit_id);
            $current = $assignmentMap[$unitId] ?? null;
            $currentAuditee = $current['auditee'] ?? null;
            $currentAnggota = $current['anggota'] ?? collect();
            $currentKetuaAuditor = $current['ketua_auditor'] ?? null;
            $currentAuditor = $current['auditor'] ?? collect();
            $periodeId = $current['periode_id'] ?? null;
        @endphp
        <div class="col-md-6 col-lg-4 unit-card" data-unit-name="{{ strtolower($unit->name) }}">
            <x-tabler.card class="h-100">
                <x-tabler.card-header>
                    <x-tabler.card-title>{!! $unit->indented_name !!}</x-tabler.card-title>
                    @if($periodeId)
                        <x-slot:actions>
                            <x-tabler.dropdown trigger="button" buttonClass="btn-sm px-2 py-1 btn-outline-primary" icon="ti ti-settings" text="Set Tim">
                                <x-tabler.dropdown-item 
                                    type="edit" 
                                    icon="ti ti-users text-warning"
                                    label="Set Tim Auditee"
                                    url="{{ route('pemutu.tim-mutu.edit-auditee', [$periodeId, $unitId]) }}" 
                                    data-modal-title="Set Tim Auditee"
                                />
                                <x-tabler.dropdown-item 
                                    type="edit" 
                                    icon="ti ti-crown text-primary"
                                    label="Set Tim Auditor"
                                    url="{{ route('pemutu.tim-mutu.edit-auditor', [$periodeId, $unitId]) }}" 
                                    data-modal-title="Set Tim Auditor"
                                />
                            </x-tabler.dropdown>
                        </x-slot:actions>
                    @endif
                </x-tabler.card-header>
                <x-tabler.card-body class="pt-3">
                    <div class="row">
                        <div class="col-6 border-end">
                            <div class="subheader mb-2">Tim Auditee</div>
                            <div class="mb-3">
                                <span class="text-muted small d-block mb-1">
                                    <i class="ti ti-user-star text-warning"></i> Auditee
                                </span>
                                @if($currentAuditee)
                                    <span class="badge bg-warning-lt fs-6 fw-normal text-truncate w-100 text-start d-block" title="{{ $currentAuditee->pegawai->nama ?? '-' }}">
                                        {{ $currentAuditee->pegawai->nama ?? '-' }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic small">-</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-muted small d-block mb-1">
                                    <i class="ti ti-users text-info"></i> Anggota
                                </span>
                                <div class="d-flex flex-column gap-1">
                                    @forelse($currentAnggota as $anggota)
                                        <span class="badge bg-info-lt fs-6 fw-normal text-truncate w-100 text-start d-block" title="{{ $anggota->pegawai->nama ?? '-' }}">
                                            {{ $anggota->pegawai->nama ?? '-' }}
                                        </span>
                                    @empty
                                        <span class="text-muted fst-italic small">-</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="subheader mb-2">Tim Auditor</div>
                            <div class="mb-3">
                                <span class="text-muted small d-block mb-1">
                                    <i class="ti ti-crown text-primary"></i> Ketua
                                </span>
                                @if($currentKetuaAuditor)
                                    <span class="badge bg-primary-lt fs-6 fw-normal text-truncate w-100 text-start d-block" title="{{ $currentKetuaAuditor->pegawai->nama ?? '-' }}">
                                        {{ $currentKetuaAuditor->pegawai->nama ?? '-' }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic small">-</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-muted small d-block mb-1">
                                    <i class="ti ti-list-search text-azure"></i> Auditor
                                </span>
                                <div class="d-flex flex-column gap-1">
                                    @forelse($currentAuditor as $auditor)
                                        <span class="badge bg-azure-lt fs-6 fw-normal text-truncate w-100 text-start d-block" title="{{ $auditor->pegawai->nama ?? '-' }}">
                                            {{ $auditor->pegawai->nama ?? '-' }}
                                        </span>
                                    @empty
                                        <span class="text-muted fst-italic small">-</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <x-tabler.empty-state title="Unit Belum Tersedia" text="Data unit organisasi tidak ditemukan." icon="ti ti-building" />
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-unit');
        
        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                const term = e.target.value.toLowerCase();
                const unitCards = document.querySelectorAll('.unit-card');

                unitCards.forEach(card => {
                    const unitName = card.dataset.unitName;
                    if (unitName.includes(term)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

    });
</script>
@endpush
