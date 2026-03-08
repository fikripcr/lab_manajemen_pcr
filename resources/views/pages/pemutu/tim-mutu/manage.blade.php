@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Kelola Tim Mutu" pretitle="Periode {{ $periode->periode }} — {{ ucfirst($periode->jenis_periode) }}">
    <x-slot:actions>
        <div class="d-flex justify-content-between align-items-center gap-2">
            <div class="input-icon">
                <span class="input-icon-addon">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" id="search-unit" class="form-control" placeholder="Cari Unit...">
            </div>
            <x-tabler.button type="back" href="{{ route('pemutu.tim-mutu.index') }}" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

    <div class="row row-cards" id="unit-cards">
        @forelse($orgUnits as $unit)
        @php
            $unitId = $unit->encrypted_org_unit_id;
            $current = $assignmentMap[$unitId] ?? null;
            $currentAuditee = $current['auditee'] ?? null;
            $currentAnggota = $current['anggota'] ?? collect();
        @endphp
        <div class="col-md-6 col-lg-4" id="unit-card-{{ $unitId }}">
            <x-tabler.card>
                <x-tabler.card-header title="{{ $unit->name }}">
                    <x-slot:actions>
                        <x-tabler.dropdown trigger="button" buttonClass="btn-sm px-2 py-1 btn-outline-primary" icon="ti ti-settings" text="Set Tim">
                            <x-tabler.dropdown-item 
                                type="edit" 
                                icon="ti ti-users text-warning"
                                label="Set Tim Auditee"
                                url="{{ route('pemutu.tim-mutu.edit-auditee', [$periode->encrypted_periodespmi_id, $unitId]) }}" 
                                data-modal-title="Set Tim Auditee"
                            />
                            <x-tabler.dropdown-item 
                                type="edit" 
                                icon="ti ti-crown text-primary"
                                label="Set Tim Auditor"
                                url="{{ route('pemutu.tim-mutu.edit-auditor', [$periode->encrypted_periodespmi_id, $unitId]) }}" 
                                data-modal-title="Set Tim Auditor"
                            />
                        </x-tabler.dropdown>
                    </x-slot:actions>
                </x-tabler.card-header>
                <x-tabler.card-body class="pt-3">
                    <div class="row">
                        <div class="col-6 border-end">
                            <div class="subheader mb-2">Tim Auditee</div>
                            {{-- Auditee --}}
                            <div class="mb-3">
                                <span class="text-muted small d-block mb-1">
                                    <i class="ti ti-user-star text-warning"></i> Auditee
                                </span>
                                @if($currentAuditee)
                                    <span class="badge bg-warning-lt fs-6 fw-normal text-truncate" style="max-width: 100%;" title="{{ $currentAuditee->pegawai->nama ?? '-' }}">
                                        {{ $currentAuditee->pegawai->nama ?? '-' }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic small">-</span>
                                @endif
                            </div>

                            {{-- Anggota --}}
                            <div class="mb-2">
                                <span class="text-muted small d-block mb-1">
                                    <i class="ti ti-users text-info"></i> Anggota
                                </span>
                                <div class="d-flex flex-column gap-1">
                                    @if($currentAnggota->count() > 0)
                                        @foreach($currentAnggota as $anggota)
                                            <span class="badge bg-info-lt fs-6 fw-normal text-truncate" style="max-width: 100%;" title="{{ $anggota->pegawai->nama ?? '-' }}">
                                                {{ $anggota->pegawai->nama ?? '-' }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted fst-italic small">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="subheader mb-2">Tim Auditor</div>
                            {{-- Ketua Auditor --}}
                            <div class="mb-3">
                                <span class="text-muted small d-block mb-1">
                                    <i class="ti ti-crown text-primary"></i> Ketua Auditor
                                </span>
                                @if(isset($current['ketua_auditor']) && $current['ketua_auditor'])
                                    <span class="badge bg-primary-lt fs-6 fw-normal text-truncate" style="max-width: 100%;" title="{{ $current['ketua_auditor']->pegawai->nama ?? '-' }}">
                                        {{ $current['ketua_auditor']->pegawai->nama ?? '-' }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic small">-</span>
                                @endif
                            </div>

                            {{-- Auditor --}}
                            <div class="mb-2">
                                <span class="text-muted small d-block mb-1">
                                    <i class="ti ti-list-search text-azure"></i> Auditor
                                </span>
                                @if(isset($current['auditor']) && $current['auditor']->count() > 0)
                                    <div class="d-flex flex-column gap-1">
                                        @foreach($current['auditor'] as $auditor)
                                            <span class="badge bg-azure-lt fs-6 fw-normal text-truncate" style="max-width: 100%;" title="{{ $auditor->pegawai->nama ?? '-' }}">
                                                {{ $auditor->pegawai->nama ?? '-' }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted fst-italic small">-</span>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
        @empty
            <div class="col-12">
                <x-tabler.empty-state
                    title="Belum ada Unit Organisasi"
                    text="Silakan tambahkan Unit Organisasi Aktif pada Data Master terlebih dahulu."
                    icon="ti ti-building"
                />
            </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $orgUnits->links('pagination::bootstrap-5') }}
    </div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-unit');
        const unitCards = document.querySelectorAll('#unit-cards .col-md-6');

        if(searchInput){
            searchInput.addEventListener('keyup', function (e) {
                const term = e.target.value.toLowerCase();

                unitCards.forEach(card => {
                    // Start from the col-md-6 wrapper, find the card-title inside
                    const titleEl = card.querySelector('.card-title');
                    if(titleEl) {
                        const title = titleEl.textContent.toLowerCase();
                        if (title.includes(term)) {
                            card.style.removeProperty('display');
                            // Also ensure parent row doesn't hide it if we had logic for that
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection
