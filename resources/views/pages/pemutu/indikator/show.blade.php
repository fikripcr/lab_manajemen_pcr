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
                                    {{ formatTanggalIndo($mon->rapat?->tgl_rapat) }} — {{ $mon->rapat?->judul_kegiatan }}
                                </div>
                            </div>
                            <a href="{{ route('Kegiatan.rapat.show', $mon->rapat?->encrypted_rapat_id) }}" class="btn btn-sm btn-white text-info fw-bold">
                                <i class="ti ti-eye me-1"></i>Detail Rapat
                            </a>
                        </div>
                    @endforeach
                @endif
                
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-muted small mb-1">No. Indikator</label>
                        <div class="fw-bold">[{{$indikator->no_indikator}}]</div>
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

                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-muted small mb-1">Jenis Data</label>
                        <div class="fw-medium">
                            @if($indikator->jenis_data)
                                @php $jdColor = $indikator->jenis_data == 'Kualitatif' ? 'blue' : 'purple'; @endphp
                                <span class="status status-{{ $jdColor }} status-lite py-0 px-2 fw-bold" style="font-size: 11px;">{{ $indikator->jenis_data }}</span>
                            @else
                                <span class="text-muted italic">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-muted small mb-1">Level Risiko</label>
                        <div>
                            @php
                                $risk = strtoupper($indikator->level_risk ?? 'NO RISK');
                                $riskColor = match ($risk) {
                                    'HIGH RISK'   => 'danger',
                                    'MEDIUM RISK' => 'warning',
                                    'LOW RISK'    => 'info',
                                    default       => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $riskColor }} text-white px-2" style="font-size: 10px;">{{ $risk }}</span>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-muted small mb-1">Sumber / Origin</label>
                        <div class="fw-medium">
                            @if($indikator->origin_from)
                                <span class="badge bg-white text-dark fw-bold border px-2">{{ $indikator->origin_from }}</span>
                            @else
                                <span class="text-muted italic">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>

        {{-- Card: Skala Capaian --}}
        <x-tabler.card class="mb-3">
            <x-tabler.card-header title='<i class="ti ti-stairs-up text-orange me-2"></i>Skala & Kriteria Capaian' />
            <x-tabler.card-body>
                @if(is_array($indikator->skala) && count($indikator->skala) > 0)
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @foreach($indikator->skala as $score => $deskripsi)
                            <div class="col">
                                <div class="p-2 border rounded-2 bg-white h-100 shadow-sm-hover transition-all">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="avatar avatar-sm bg-{{ $score >= 4 ? 'green' : ($score == 3 ? 'blue' : ($score == 2 ? 'orange' : 'red')) }}-lt fw-bold flex-shrink-0">
                                            {{ $score }}
                                        </div>
                                        <div class="flex-fill min-w-0">
                                            <div class="text-reset d-block text-wrap small fw-medium text-dark lh-sm mb-1" style="max-height: 80px; overflow-y: auto; scrollbar-width: thin;">
                                                {!! $deskripsi !!}
                                            </div>
                                            <div class="text-muted smaller opacity-75">
                                                Nilai/Skor {{ $score }} Point
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted small fst-italic">
                        Indikator ini tidak memiliki rincian definisi Skala Capaian.
                    </div>
                @endif
            </x-tabler.card-body>
        </x-tabler.card>

        {{-- Card: Unit / Pegawai Penanggung Jawab (Moved from Sidebar for Balance) --}}
        @if($indikator->type != 'performa')
            <x-tabler.card class="mb-3">
                <x-tabler.card-header title='<i class="ti ti-building text-blue me-2"></i>Unit Kerja & Target' />
                <x-tabler.datatable-client
                    id="table-unit-main"
                    :columns="[
                        ['name' => 'Unit / Lembaga', 'width' => '70%'],
                        ['name' => 'Target', 'class' => 'text-center', 'width' => '30%']
                    ]"
                >
                    @forelse($indikator->orgUnits as $unit)
                        <tr>
                            <td data-label="Unit">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-building me-2 text-muted-dark opacity-50"></i>
                                    <span class="fw-bold text-dark">{{ $unit->name }}</span>
                                </div>
                            </td>
                            <td data-label="Target" class="text-center">
                                <span class="badge badge-outline text-blue px-3">{{ $unit->pivot->target ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        {{-- Handled by card-body text below --}}
                    @endforelse
                </x-tabler.datatable-client>

                @if($indikator->orgUnits->isEmpty())
                    <x-tabler.card-body class="text-center text-muted py-4 small italic">
                        Belum ada unit penanggung jawab yang ditugaskan.
                    </x-tabler.card-body>
                @endif
            </x-tabler.card>
        @else
            <x-tabler.card class="mb-3">
                <x-tabler.card-header title='<i class="ti ti-users-group text-purple me-2"></i>Penugasan Pegawai' />
                <x-tabler.datatable-client
                    id="table-pegawai-kpi-main"
                    :columns="[
                        ['name' => 'Pegawai', 'width' => '60%'],
                        ['name' => 'Bobot/Target', 'class' => 'text-center', 'width' => '40%']
                    ]"
                >
                    @forelse($indikator->pegawai as $ip)
                    <tr>
                        <td>
                            <div class="d-flex py-1 align-items-center">
                                <span class="avatar me-2 avatar-sm" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($ip->pegawai->nama) }})"></span>
                                <div class="flex-fill">
                                    <div class="font-weight-medium text-dark" style="line-height: 1.2;">{{ $ip->pegawai->nama }}</div>
                                    <div class="text-muted smaller">NIP: {{ $ip->pegawai->nip ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="small">
                                <span class="badge bg-purple-lt me-1">Bobot {{ $ip->weight }}%</span>
                                <span class="badge bg-green-lt">Tgt: {{ $ip->target_value }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                        {{-- Handled by card-body text below --}}
                    @endforelse
                </x-tabler.datatable-client>
                
                @if($indikator->pegawai->isEmpty())
                    <x-tabler.card-body class="text-center text-muted py-4 small italic">
                        Belum ada pegawai yang ditugaskan.
                    </x-tabler.card-body>
                @endif
            </x-tabler.card>
        @endif
    </div>

    {{-- Sidebar Columns --}}
    <div class="col-lg-4">
        {{-- Card: Labels --}}
        <x-tabler.card class="mb-3">
            <x-tabler.card-header title='<i class="ti ti-tags text-blue me-2"></i>Label' />
            <x-tabler.card-body>
                <div class="d-flex flex-wrap gap-2">
                {!! pemutuDtColLabelsList($indikator) !!}
                </div>
            </x-tabler.card-body>
        </x-tabler.card>

        {{-- Card: Referensi Dokumen --}}
        <x-tabler.card class="mb-3">
            <x-tabler.card-header title='<i class="ti ti-link text-indigo me-2"></i>Dokumen Terkait' />
            <div class="list-group list-group-flush">
                {{-- Direct Renstra Mapping --}}
                @if($indikator->renstraPoin)
                    <a href="{{ route('pemutu.dokumen.index', ['tabs' => 'renstra']) }}#tree-node-sub-{{ $indikator->renstraPoin->encrypted_doksub_id }}" class="list-group-item list-group-item-action d-flex flex-column align-items-start py-3 border-bottom border-2 border-azure-lt bg-azure-lt-light">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                            <span class="badge bg-azure text-white">RENSTRA</span>
                            <span class="text-azure small fw-bold"><i class="ti ti-pin me-1"></i>Mapped</span>
                        </div>
                        <div class="d-block w-100 text-truncate text-dark fw-bold">
                            {{ $indikator->renstraPoin->dokumen->judul ?? 'Renstra Dokumen' }}
                        </div>
                        <div class="text-azure small mt-1 text-wrap line-clamp-2">
                            <i class="ti ti-corner-down-right me-1"></i>
                            Poin: {{ $indikator->renstraPoin->judul }}
                        </div>
                    </a>
                @endif

                {{-- Other Document Junctions --}}
                @forelse($indikator->dokSubs as $relSub)
                    @php
                        $tabName = \App\Config\PemutuDokumenConfig::for($relSub->dokumen->jenis)->category();
                    @endphp
                    <a href="{{ route('pemutu.dokumen.index', ['tabs' => $tabName]) }}#tree-node-sub-{{ $relSub->encrypted_doksub_id }}" class="list-group-item list-group-item-action d-flex flex-column align-items-start py-3">
                        <span class="badge bg-purple-lt mb-2">{{ \App\Config\PemutuDokumenConfig::for($relSub->dokumen->jenis)->label() }}</span>
                        <div class="d-block w-100 text-truncate text-dark fw-medium">
                            {{ $relSub->dokumen->judul }}
                        </div>
                        <div class="text-muted small mt-1 text-wrap line-clamp-2">
                            <i class="ti ti-corner-down-right me-1"></i>
                            Poin: {{ $relSub->judul }}
                        </div>
                    </a>
                @empty
                    @if(!$indikator->renstraPoin)
                        <x-tabler.card-body class="text-center py-4 small italic text-muted">
                            Tidak ada referensi dokumen.
                        </x-tabler.card-body>
                    @endif
                @endforelse
            </div>
        </x-tabler.card>




    </div>
</div>
@endsection
