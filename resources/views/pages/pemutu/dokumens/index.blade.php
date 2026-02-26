@extends('layouts.tabler.app')

@section('title', 'Dokumen SPMI')

@php
    $pageLabel = $activeTab === 'standar' ? 'Standar' : 'Kebijakan';
@endphp

@section('header')
<x-tabler.page-header title="Dokumen {{ $pageLabel }}" pretitle="Penetapan">
    <x-slot:actions>
        <div class="d-flex justify-content-between align-items-center gap-2">
            <x-tabler.form-select name="periode" class="mb-0 filter-sync-param" data-param="periode" data-base-url="{{ route('pemutu.dokumens.index', ['tabs' => $activeTab]) }}">
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
            @if($activeTab === 'standar')
                <x-tabler.button type="create" text="Standar" class="btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'tabs' => 'standar']) }}" data-modal-title="Dokumen Standar" />
            @else
                <x-tabler.button type="create" text="Kebijakan" class="btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'tabs' => 'kebijakan']) }}" data-modal-title="Tambah Dokumen Kebijakan" />
            @endif
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <!-- Tree View Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            @if($activeTab === 'kebijakan')
                <div class="card-body border-bottom bg-transparent py-3">
                    <ul class="nav nav-pills nav-fill" data-bs-toggle="tabs">
                        <li class="nav-item">
                            <a href="#tab-visi-misi" class="nav-link py-1 {{ !request('jenis') || request('jenis') == 'visi-misi' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="visi-misi">VISI &amp; MISI</a>
                        </li>
                        @foreach(['rjp' => 'RPJP', 'renstra' => 'RENSTRA', 'renop' => 'RENOP'] as $key => $label)
                        <li class="nav-item">
                            <a href="#tab-{{ $key }}" class="nav-link py-1 {{ request('jenis') == $key ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="{{ $key }}">{{ $label }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-body p-0 overflow-auto" style="max-height: 55vh;">
                    <div class="tab-content p-3">
                        <div class="tab-pane {{ !request('jenis') || request('jenis') == 'visi-misi' ? 'active show' : '' }}" id="tab-visi-misi">
                           <ul class="list-unstyled nested-sortable mb-0">
                               @foreach($dokumentByJenis['visi'] ?? [] as $dok)
                                   @include('pages.pemutu.dokumens._tree_item', ['dok' => $dok, 'level' => 0])
                               @endforeach
                               @if(empty($dokumentByJenis['visi']))
                                   <li class="text-muted text-center py-3">Tidak ada dokumen VISI.</li>
                               @endif
                           </ul>
                        </div>
                        @foreach(['rjp', 'renstra', 'renop'] as $jenis)
                        <div class="tab-pane {{ request('jenis') == $jenis ? 'active show' : '' }}" id="tab-{{ $jenis }}">
                           <ul class="list-unstyled nested-sortable mb-0">
                               @forelse($dokumentByJenis[$jenis] ?? [] as $dok)
                                   @include('pages.pemutu.dokumens._tree_item', ['dok' => $dok, 'level' => 0, 'collapsed' => true])
                               @empty
                                   <li class="text-muted text-center py-3">Tidak ada dokumen {{ strtoupper($jenis) }}.</li>
                               @endforelse
                           </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="card-body border-bottom bg-transparent py-3">
                    <ul class="nav nav-pills nav-fill" data-bs-toggle="tabs">
                        @php $jenisAktif = request('jenis', 'standar'); @endphp
                        <li class="nav-item"><a href="#std-standar" class="nav-link py-1 {{ $jenisAktif === 'standar' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="standar">Standar</a></li>
                        <li class="nav-item"><a href="#std-formulir" class="nav-link py-1 {{ $jenisAktif === 'formulir' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="formulir">Formulir</a></li>
                        <li class="nav-item"><a href="#std-manual_prosedur" class="nav-link py-1 {{ $jenisAktif === 'manual_prosedur' ? 'active' : '' }}" data-bs-toggle="tab" data-jenis="manual_prosedur">Manual Prosedur</a></li>
                    </ul>
                </div>
                <div class="card-body p-0 overflow-auto" style="max-height: 55vh;">
                    <div class="tab-content p-3">
                        @foreach(['standar', 'formulir', 'manual_prosedur'] as $stType)
                        @php
                            $isInitialActive = $jenisAktif === $stType;
                            $labelStandar    = ['manual_prosedur' => 'Manual Prosedur', 'formulir' => 'Formulir', 'standar' => 'Standar'][$stType] ?? ucfirst($stType);
                        @endphp
                        <div class="tab-pane {{ $isInitialActive ? 'active show' : '' }}" id="std-{{ $stType }}">
                            <ul class="list-unstyled nested-sortable mb-0">
                                @forelse($dokumentByJenis[$stType] ?? [] as $dok)
                                    @include('pages.pemutu.dokumens._tree_item', ['dok' => $dok, 'level' => 0, 'collapsed' => true])
                                @empty
                                    <li class="text-muted text-center py-3">Tidak ada dokumen {{ $labelStandar }}.</li>
                                @endforelse
                            </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Panel -->
    <div class="col-lg-8">
        <div id="document-detail-panel">
            <div class="card">
                <div class="card-body text-center py-5">
                    <p class="text-muted">Pilih dokumen untuk melihat detail.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
// Inisialisasi workspace Dokumen SPMI
window.{
    reorderUrl: '{{ route("pemutu.dokumens.reorder") }}'
});
</script>
@endpush
