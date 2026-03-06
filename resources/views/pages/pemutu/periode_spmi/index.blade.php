@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button type="create" href="#" class="ajax-modal-btn" data-url="{{ route('pemutu.periode-spmi.create') }}" data-modal-title="Tambah Periode SPMI" text="Tambah Periode" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    @forelse($periodes as $periode)
        <div class="col-md-6">
            <div class="card card-md shadow-sm border-0 overflow-hidden h-100 position-relative">
                {{-- Decorative Background Gradient --}}
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-05 pointer-events-none bg-gradient-{{ $periode->jenis_periode === 'Akademik' ? 'cyan' : 'indigo' }}"></div>
                
                {{-- Card Header --}}
                <div class="card-header bg-transparent border-0 pb-0">
                    <div>
                        <div class="text-uppercase text-muted font-weight-bold tracking-widest small mb-1">Periode {{ $periode->jenis_periode }}</div>
                        <h2 class="card-title h1 mb-0 text-primary">Periode {{ $periode->periode }}</h2>
                    </div>
                    <div class="card-actions">
                        <div class="dropdown">
                            <a href="#" class="btn btn-icon btn-ghost-secondary rounded-circle dropdown-toggle no-caret" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                <a class="dropdown-item ajax-modal-btn" href="#" data-url="{{ route('pemutu.periode-spmi.edit', $periode->encrypted_periodespmi_id) }}">
                                    <i class="ti ti-pencil me-2 text-muted"></i> Edit Periode
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger ajax-delete" href="#" 
                                   data-url="{{ route('pemutu.periode-spmi.destroy', $periode->encrypted_periodespmi_id) }}"
                                   data-title="Hapus Periode?"
                                   data-text="Data periode dan seluruh data terkait mungkin akan terpengaruh.">
                                    <i class="ti ti-trash me-2"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="steps steps-vertical">
                        {{-- 1. PENETAPAN --}}
                        <div class="step-item active">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm rounded-circle bg-primary-lt shadow-sm">
                                        <i class="ti ti-gavel"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-bold">Penetapan</div>
                                    <div class="text-muted small">
                                        <i class="ti ti-calendar-event me-1"></i>
                                        {{ $periode->penetapan_awal ? \Carbon\Carbon::parse($periode->penetapan_awal)->translatedFormat('d M Y') : '-' }} 
                                        &mdash; 
                                        {{ $periode->penetapan_akhir ? \Carbon\Carbon::parse($periode->penetapan_akhir)->translatedFormat('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. PELAKSANAAN --}}
                        <div class="step-item active">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm rounded-circle bg-teal-lt shadow-sm">
                                        <i class="ti ti-player-play"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-bold">Pelaksanaan</div>
                                    <div class="text-muted small">Berjalan sepanjang Tahun {{ $periode->periode }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. EVALUASI --}}
                        <div class="step-item @if($periode->ed_awal || $periode->ami_awal) active @endif">
                            <div class="row g-3">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm rounded-circle @if($periode->ed_awal || $periode->ami_awal) bg-warning-lt shadow-sm @else bg-light text-muted @endif">
                                        <i class="ti ti-clipboard-check"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-bold">Evaluasi (ED & AMI)</div>
                                    <div class="row mt-2 g-2">
                                        <div class="col-sm-6">
                                            <div class="p-2 rounded bg-light-subtle border border-dashed border-warning">
                                                <div class="small font-weight-bold text-warning-emphasis text-uppercase tracking-wider">Evaluasi Diri</div>
                                                <div class="small text-muted">
                                                    @if($periode->ed_awal)
                                                        {{ \Carbon\Carbon::parse($periode->ed_awal)->format('d M') }} - {{ \Carbon\Carbon::parse($periode->ed_akhir)->format('d M') }}
                                                    @else
                                                        <span class="fst-italic">Not scheduled</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="p-2 rounded bg-light-subtle border border-dashed border-warning">
                                                <div class="small font-weight-bold text-warning-emphasis text-uppercase tracking-wider">AMI</div>
                                                <div class="small text-muted">
                                                    @if($periode->ami_awal)
                                                        {{ \Carbon\Carbon::parse($periode->ami_awal)->format('d M') }} - {{ \Carbon\Carbon::parse($periode->ami_akhir)->format('d M') }}
                                                    @else
                                                        <span class="fst-italic">Not scheduled</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 4. PENGENDALIAN --}}
                        <div class="step-item @if($periode->pengendalian_awal) active @endif">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm rounded-circle @if($periode->pengendalian_awal) bg-danger-lt shadow-sm @else bg-light text-muted @endif">
                                        <i class="ti ti-settings-exclamation"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-bold">Pengendalian (RTM)</div>
                                    <div class="text-muted small">
                                        @if($periode->pengendalian_awal)
                                            <i class="ti ti-calendar-event me-1"></i>
                                            {{ \Carbon\Carbon::parse($periode->pengendalian_awal)->translatedFormat('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($periode->pengendalian_akhir)->translatedFormat('d M Y') }}
                                        @else
                                            <span class="fst-italic">Schedule pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 5. PENINGKATAN --}}
                        <div class="step-item @if($periode->peningkatan_awal) active @endif">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm rounded-circle @if($periode->peningkatan_awal) bg-success-lt shadow-sm @else bg-light text-muted @endif">
                                        <i class="ti ti-trending-up"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-bold">Peningkatan</div>
                                    <div class="text-muted small">
                                        @if($periode->peningkatan_awal)
                                            <i class="ti ti-calendar-event me-1"></i>
                                            {{ \Carbon\Carbon::parse($periode->peningkatan_awal)->translatedFormat('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($periode->peningkatan_akhir)->translatedFormat('d M Y') }}
                                        @else
                                            <span class="fst-italic">Schedule pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <x-tabler.empty-state
            title="Belum ada Periode"
            text="Silakan tambahkan periode baru untuk memulai siklus SPMI."
            icon="ti ti-calendar-time"
        />
    @endforelse
</div>
@endsection
