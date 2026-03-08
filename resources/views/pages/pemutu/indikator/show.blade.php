@extends('layouts.tabler.app')
@section('title', 'Detail Indikator: ' . ($indikator->no_indikator ?? 'N/A'))

@section('header')
<x-tabler.page-header title="Detail Indikator" pretitle="SPMI">
    <x-slot:actions>
        <x-tabler.button type="back" />
        <x-tabler.button href="{{ route('pemutu.indikator.edit', $indikator->encrypted_indikator_id) }}" icon="ti ti-pencil" text="Edit Indikator" class="btn-primary" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-lg-8">
        {{-- Card: Informasi Indikator --}}
        <x-tabler.card class="mb-3">
            <x-tabler.card-header title='<i class="ti ti-info-circle text-primary me-2"></i>Informasi Indikator' />
            <x-tabler.card-body>
                <div class="mb-3">
                    <label class="form-label text-muted fw-bold">Pernyataan Standar</label>
                    <div class="fs-3 fw-bold text-dark">{{ $indikator->indikator }}</div>
                    @if($indikator->keterangan)
                        <div class="mt-2 text-secondary bg-light p-2 rounded small">
                            {!! $indikator->keterangan !!}
                        </div>
                    @endif
                </div>

                {{-- Monitoring Alert (Full Page) --}}
                @if(isset($monitorings) && $monitorings->isNotEmpty())
                    @foreach($monitorings as $mon)
                        <div class="alert alert-important alert-info mb-4 d-flex align-items-center justify-content-between p-2 px-3">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-broadcast fs-3 me-2"></i>
                                <div>
                                    <span class="fw-bold">Indikator ini dalam Pemantauan:</span> 
                                    {{ $mon->tgl_rapat->format('d M Y') }} — {{ $mon->judul_kegiatan }}
                                </div>
                            </div>
                            <a href="{{ route('Kegiatan.rapat.show', $mon->encrypted_rapat_id) }}" class="btn btn-sm btn-white text-info fw-bold">
                                <i class="ti ti-eye me-1"></i>Detail Rapat
                            </a>
                        </div>
                    @endforeach
                @endif
                
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-muted small mb-1">No. Indikator</label>
                        <div class="fw-medium font-monospace">{{ $indikator->no_indikator ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-muted small mb-1">Tipe / Jenis</label>
                        <div>
                            @php $typeInfo = pemutuIndikatorTypeInfo($indikator->type); @endphp
                            <span class="badge bg-{{ $typeInfo['color'] }}-lt" title="{{ $typeInfo['label'] }}">{{ $typeInfo['label'] }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-muted small mb-1">Kelompok</label>
                        <div class="fw-medium">
                            @if(strtolower($indikator->kelompok_indikator) == 'akademik')
                                <span class="text-blue"><i class="ti ti-book me-1"></i>Akademik</span>
                            @elseif(strtolower($indikator->kelompok_indikator) == 'non_akademik' || strtolower($indikator->kelompok_indikator) == 'non-akademik')
                                <span class="text-orange"><i class="ti ti-briefcase me-1"></i>Non-Akademik</span>
                            @else
                                {{ $indikator->kelompok_indikator ?? '-' }}
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-muted small mb-1">Periode Pelaksanaan</label>
                        <div class="fw-medium">
                            @if($indikator->periode_mulai && $indikator->periode_selesai)
                                <i class="ti ti-calendar me-1 text-muted"></i>{{ $indikator->periode_mulai }} s/d {{ $indikator->periode_selesai }}
                            @else
                                <span class="text-muted italic">Sesuai Siklus SPMI Aktif</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-muted small mb-1">Indikator Induk</label>
                        <div class="fw-medium text-truncate">
                            @if($indikator->parent)
                                <a href="{{ route('pemutu.indikator.show', $indikator->parent->encrypted_indikator_id) }}" title="{{ $indikator->parent->indikator }}">
                                    [{{ $indikator->parent->no_indikator }}] {{ \Str::limit($indikator->parent->indikator, 30) }}
                                </a>
                            @else
                                <span class="text-muted"><i class="ti ti-crown me-1"></i>Tingkat Utama</span>
                            @endif
                        </div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>

        {{-- Card: Skala Capaian --}}
        <x-tabler.card class="mb-3">
            <x-tabler.card-header title='<i class="ti ti-stairs-up text-orange me-2"></i>Skala & Kriteria Capaian' />
            @if(is_array($indikator->skala) && count($indikator->skala) > 0)
                <div class="list-group list-group-flush list-group-hoverable">
                    @foreach($indikator->skala as $score => $deskripsi)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="avatar bg-{{ $score >= 4 ? 'green' : ($score == 3 ? 'blue' : ($score == 2 ? 'orange' : 'red')) }}-lt fw-bold">
                                        {{ $score }}
                                    </div>
                                </div>
                                <div class="col text-truncate">
                                    <div class="text-reset d-block text-wrap indicator-scroll">
                                        {!! $deskripsi !!}
                                    </div>
                                    <div class="text-muted text-wrap mt-1 small">
                                        Nilai/Skor {{ $score }} Point
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card-body text-center py-4 text-muted small">
                    Indikator ini tidak memiliki rincian definisi Skala Capaian.
                </div>
            @endif
        </x-tabler.card>
    </div>

    {{-- Sidebar Columns --}}
    <div class="col-lg-4">
        {{-- Card: Labels --}}
        <x-tabler.card class="mb-3">
            <x-tabler.card-header title='<i class="ti ti-tags text-blue me-2"></i>Label & Kategori' />
            <x-tabler.card-body>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($indikator->labels as $label)
                        <span class="badge bg-blue-lt">{{ $label->name }}</span>
                    @empty
                        <span class="text-muted small italic">Tidak ada label.</span>
                    @endforelse
                </div>
            </x-tabler.card-body>
        </x-tabler.card>

        {{-- Card: Unit / Pegawai Penanggung Jawab --}}
        @if($indikator->type != 'performa')
            <x-tabler.card class="mb-3">
                <x-tabler.card-header title='<i class="ti ti-building text-blue me-2"></i>Unit Kerja & Target' />
                <x-tabler.datatable-client
                    id="table-unit-sidebar"
                    :columns="[
                        ['name' => 'Unit / Lembaga'],
                        ['name' => 'Target', 'class' => 'text-center']
                    ]"
                >
                    @forelse($indikator->orgUnits as $unit)
                        <tr>
                            <td data-label="Unit">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-building me-1 text-muted"></i>
                                    <span class="fw-medium">{{ $unit->name }}</span>
                                </div>
                            </td>
                            <td data-label="Target" class="text-center">
                                <span class="badge badge-outline text-blue">{{ $unit->pivot->target ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        {{-- Handled by card-body text below --}}
                    @endforelse
                </x-tabler.datatable-client>

                @if($indikator->orgUnits->isEmpty())
                    <div class="text-center text-muted py-4 small italic">
                        Belum ada unit penanggung jawab yang ditugaskan.
                    </div>
                @endif
            </x-tabler.card>
        @else
            <x-tabler.card class="mb-3">
                <x-tabler.card-header title='<i class="ti ti-users-group text-purple me-2"></i>Penugasan Pegawai' />
                <x-tabler.datatable-client
                    id="table-pegawai-kpi-sidebar"
                    :columns="[
                        ['name' => 'Pegawai'],
                        ['name' => 'Bobot/Target', 'class' => 'text-center']
                    ]"
                >
                    @forelse($indikator->pegawai as $ip)
                    <tr>
                        <td>
                            <div class="d-flex py-1 align-items-center">
                                <span class="avatar me-2 avatar-sm" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($ip->pegawai->nama) }})"></span>
                                <div class="flex-fill">
                                    <div class="font-weight-medium" style="line-height: 1.2;">{{ $ip->pegawai->nama }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="text-muted small">
                                <strong>{{ $ip->weight }}%</strong> <br> Tgt: {{ $ip->target_value }}
                            </div>
                        </td>
                    </tr>
                    @empty
                        {{-- Handled by card-body text below --}}
                    @endforelse
                </x-tabler.datatable-client>
                
                @if($indikator->pegawai->isEmpty())
                    <div class="text-center text-muted py-4 small italic">
                        Belum ada pegawai yang ditugaskan.
                    </div>
                @endif
            </x-tabler.card>
        @endif

        {{-- Card: Referensi Dokumen --}}
        <x-tabler.card class="mb-3">
            <x-tabler.card-header title='<i class="ti ti-link text-indigo me-2"></i>Referensi Dokumen Terkait' />
            <div class="list-group list-group-flush">
                @forelse($indikator->dokSubs as $relSub)
                    @php
                        $tabName = pemutuTabByJenis($relSub->dokumen->jenis) ?? 'kebijakan';
                    @endphp
                    <a href="{{ route('pemutu.dokumen.index', ['tabs' => $tabName]) }}#tree-node-sub-{{ $relSub->encrypted_doksub_id }}" class="list-group-item list-group-item-action d-flex flex-column align-items-start py-3">
                        <span class="badge bg-purple-lt mb-2">{{ pemutuJenisLabel($relSub->dokumen->jenis) }}</span>
                        <div class="d-block w-100 text-truncate text-dark fw-medium">
                            {{ $relSub->dokumen->judul }}
                        </div>
                        <div class="text-muted small mt-1 text-wrap line-clamp-2">
                            <i class="ti ti-corner-down-right me-1"></i>
                            Poin: {{ $relSub->judul }}
                        </div>
                    </a>
                @empty
                    <div class="card-body text-center py-4 small italic text-muted">
                        Tidak ada referensi dokumen.
                    </div>
                @endforelse
            </div>
        </x-tabler.card>
    </div>
</div>
@endsection
