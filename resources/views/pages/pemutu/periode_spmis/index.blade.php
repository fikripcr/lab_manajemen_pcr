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
            <div class="card card-stacked">
                <div class="card-status-top bg-primary"></div>
                
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

                {{-- Card Body --}}
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        {{-- 1. PENETAPAN --}}
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar bg-primary text-white">P</span>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">Penetapan</div>
                                    <div class="text-muted small">
                                        {{ $periode->penetapan_awal ? \Carbon\Carbon::parse($periode->penetapan_awal)->translatedFormat('d M Y') : '-' }} 
                                        s/d 
                                        {{ $periode->penetapan_akhir ? \Carbon\Carbon::parse($periode->penetapan_akhir)->translatedFormat('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. PELAKSANAAN --}}
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar bg-teal text-white">P</span>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">Pelaksanaan</div>
                                    <div class="text-muted small">
                                        {{-- Custom Logic or Placeholder --}}
                                        Sepanjang Periode {{ $periode->periode }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. EVALUASI (ED & AMI) --}}
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar bg-warning text-white">E</span>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">Evaluasi (AMI & ED)</div>
                                    <div class="text-muted small">
                                        @if($periode->ed_awal)
                                            <div>ED: {{ \Carbon\Carbon::parse($periode->ed_awal)->format('d M') }} - {{ \Carbon\Carbon::parse($periode->ed_akhir)->format('d M') }}</div>
                                        @endif
                                        @if($periode->ami_awal)
                                            <div>AMI: {{ \Carbon\Carbon::parse($periode->ami_awal)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($periode->ami_akhir)->translatedFormat('d M Y') }}</div>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 4. PENGENDALIAN --}}
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar bg-danger text-white">P</span>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">Pengendalian (RTM)</div>
                                    <div class="text-muted small">
                                        {{ $periode->pengendalian_awal ? \Carbon\Carbon::parse($periode->pengendalian_awal)->translatedFormat('d M Y') : '-' }}
                                        s/d
                                        {{ $periode->pengendalian_akhir ? \Carbon\Carbon::parse($periode->pengendalian_akhir)->translatedFormat('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                         {{-- 5. PENINGKATAN --}}
                         <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar bg-success text-white">P</span>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">Peningkatan</div>
                                    <div class="text-muted small">
                                        {{ $periode->peningkatan_awal ? \Carbon\Carbon::parse($periode->peningkatan_awal)->translatedFormat('d M Y') : '-' }}
                                        s/d
                                        {{ $periode->peningkatan_akhir ? \Carbon\Carbon::parse($periode->peningkatan_akhir)->translatedFormat('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
