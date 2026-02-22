@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="#" class="ajax-modal-btn btn-primary" data-url="{{ route('pemutu.periode-spmis.create') }}" data-modal-title="Tambah Periode SPMI" icon="ti ti-plus" text="Tambah Periode" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    @forelse($periodes as $periode)
        <div class="col-md-6 col-lg-4">
            <div class="card ">
                {{-- Card Header --}}
                <div class="card-header">
                    <h3 class="card-title">
                        Periode {{ $periode->periode }}
                        <span class="badge bg-blue-lt ms-2">{{ $periode->jenis_periode }}</span>
                    </h3>
                    <div class="card-actions">
                        <div class="dropdown">
                            <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item ajax-modal-btn" href="#" data-url="{{ route('pemutu.periode-spmis.edit', $periode->encrypted_periodespmi_id) }}">
                                    <i class="ti ti-pencil me-2"></i> Edit
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger ajax-delete" href="#" 
                                   data-url="{{ route('pemutu.periode-spmis.destroy', $periode->encrypted_periodespmi_id) }}"
                                   data-title="Hapus Periode?"
                                   data-text="Data periode dan seluruh data terkait mungkin akan terpengaruh.">
                                    <i class="ti ti-trash me-2"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="timeline">
                        {{-- 1. PENETAPAN --}}
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-primary-lt">
                                <i class="ti ti-gavel"></i>
                            </div>
                            <div class="timeline-event-card shadow-none border">
                                <div class="card-body p-2">
                                    <div class="fw-bold">Penetapan</div>
                                    <div class="text-muted small">
                                        {{ $periode->penetapan_awal ? \Carbon\Carbon::parse($periode->penetapan_awal)->translatedFormat('d M Y') : '-' }} 
                                        s/d 
                                        {{ $periode->penetapan_akhir ? \Carbon\Carbon::parse($periode->penetapan_akhir)->translatedFormat('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </li>

                        {{-- 2. PELAKSANAAN --}}
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-teal-lt">
                                <i class="ti ti-player-play"></i>
                            </div>
                            <div class="timeline-event-card shadow-none border">
                                <div class="card-body p-2">
                                    <div class="fw-bold">Pelaksanaan</div>
                                    <div class="text-muted small">
                                        Sepanjang Periode {{ $periode->periode }}
                                    </div>
                                </div>
                            </div>
                        </li>

                        {{-- 3. EVALUASI --}}
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-warning-lt">
                                <i class="ti ti-clipboard-check"></i>
                            </div>
                            <div class="timeline-event-card shadow-none border">
                                <div class="card-body p-2">
                                    <div class="fw-bold">Evaluasi (AMI & ED)</div>
                                    <div class="text-muted small">
                                        @if($periode->ed_awal)
                                            <div><span class="text-warning">ED:</span> {{ \Carbon\Carbon::parse($periode->ed_awal)->format('d M') }} - {{ \Carbon\Carbon::parse($periode->ed_akhir)->format('d M') }}</div>
                                        @endif
                                        @if($periode->ami_awal)
                                            <div><span class="text-warning">AMI:</span> {{ \Carbon\Carbon::parse($periode->ami_awal)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($periode->ami_akhir)->translatedFormat('d M Y') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>

                        {{-- 4. PENGENDALIAN --}}
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-danger-lt">
                                <i class="ti ti-settings-exclamation"></i>
                            </div>
                            <div class="timeline-event-card shadow-none border">
                                <div class="card-body p-2">
                                    <div class="fw-bold">Pengendalian (RTM)</div>
                                    <div class="text-muted small">
                                        {{ $periode->pengendalian_awal ? \Carbon\Carbon::parse($periode->pengendalian_awal)->translatedFormat('d M Y') : '-' }}
                                        s/d
                                        {{ $periode->pengendalian_akhir ? \Carbon\Carbon::parse($periode->pengendalian_akhir)->translatedFormat('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </li>

                        {{-- 5. PENINGKATAN --}}
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-success-lt">
                                <i class="ti ti-trending-up"></i>
                            </div>
                            <div class="timeline-event-card shadow-none border">
                                <div class="card-body p-2">
                                    <div class="fw-bold">Peningkatan</div>
                                    <div class="text-muted small">
                                        {{ $periode->peningkatan_awal ? \Carbon\Carbon::parse($periode->peningkatan_awal)->translatedFormat('d M Y') : '-' }}
                                        s/d
                                        {{ $periode->peningkatan_akhir ? \Carbon\Carbon::parse($periode->peningkatan_akhir)->translatedFormat('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                
                {{-- Card Footer --}}
                <div class="card-footer">
                   <div class="row align-items-center">
                      <div class="col-auto">
                         <span class="text-muted small">Dibuat: {{ $periode->created_at->diffForHumans() }}</span>
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
