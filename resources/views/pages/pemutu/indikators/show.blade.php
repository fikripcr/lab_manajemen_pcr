@if(request()->ajax() || request()->has('ajax'))
    <div class="modal-header">
        <h5 class="modal-title">Detail Indikator: {{ $indikator->no_indikator }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-4">
            <label class="form-label text-muted small uppercase">Pernyataan Standar</label>
            <div class="fs-3 fw-bold">{{ $indikator->indikator }}</div>
        </div>

        <div class="datagrid mb-4">
            <div class="datagrid-item">
                <div class="datagrid-title">No. Indikator</div>
                <div class="datagrid-content">{{ $indikator->no_indikator ?? '-' }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Tipe / Jenis</div>
                <div class="datagrid-content">
                    @if($indikator->type == 'renop')
                        <span class="badge bg-green-lt">Indikator Renop</span>
                    @elseif($indikator->type == 'standar')
                        <span class="badge bg-blue-lt">Indikator Standar</span>
                    @elseif($indikator->type == 'performa')
                        <span class="badge bg-purple-lt">Indikator Performa (KPI)</span>
                    @endif
                </div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Indikator Induk</div>
                <div class="datagrid-content">
                    {{ $indikator->parent ? '[' . $indikator->parent->no_indikator . '] ' . \Str::limit($indikator->parent->indikator, 50) : 'Tingkat Utama' }}
                </div>
            </div>
        </div>

        @if($indikator->orgUnits->count() > 0)
            <div class="mb-3">
                <h4 class="card-title mb-2">Unit Kerja & Target</h4>
                <div class="border rounded">
                    <x-tabler.datatable-client
                        id="table-unit-modal"
                        :columns="[
                            ['name' => 'Unit / Lembaga'],
                            ['name' => 'Target','class' => 'text-center']
                        ]"
                    >
                        @foreach($indikator->orgUnits as $unit)
                            <tr>
                                <td>{{ $unit->name }}</td>
                                <td><span class="badge badge-outline text-blue">{{ $unit->pivot->target ?? '-' }}</span></td>
                            </tr>
                        @endforeach
                    </x-tabler.datatable-client>
                </div>
            </div>
        @endif

        @if($indikator->labels->count() > 0)
            <div class="mt-3">
                <div class="d-flex flex-wrap gap-1">
                    @foreach($indikator->labels as $label)
                        <span class="badge bg-blue-lt">{{ $label->name }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="modal-footer">
        <x-tabler.button type="cancel" data-bs-dismiss="modal" text="Tutup" />
        <x-tabler.button :href="route('pemutu.indikators.show', $indikator->encrypted_indikator_id)" text="Detail Lengkap" />
    </div>
@else
    @extends('layouts.tabler.app')
    @section('title', 'Detail Indikator')

    @section('header')
    <x-tabler.page-header title="Detail Indikator" pretitle="SPMI">
        <x-slot:actions>
            <x-tabler.button href="javascript:history.back()" icon="ti ti-arrow-left" text="Kembali" class="btn-secondary" />
            <x-tabler.button href="{{ route('pemutu.indikators.edit', $indikator->encrypted_indikator_id) }}" icon="ti ti-pencil" text="Edit Indikator" class="btn-primary" />
        </x-slot:actions>
    </x-tabler.page-header>
    @endsection

    @section('content')
    <div class="row row-cards">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-info-circle text-primary me-2"></i>Informasi Indikator</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold">Pernyataan Standar</label>
                        <div class="fs-3 fw-bold text-dark">{{ $indikator->indikator }}</div>
                        @if($indikator->keterangan)
                            <div class="mt-2 text-secondary bg-light p-2 rounded small">
                                {!! $indikator->keterangan !!}
                            </div>
                        @endif
                    </div>
                    
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
                                    <a href="{{ route('pemutu.indikators.show', $indikator->parent->encrypted_indikator_id) }}" title="{{ $indikator->parent->indikator }}">
                                        [{{ $indikator->parent->no_indikator }}] {{ \Str::limit($indikator->parent->indikator, 30) }}
                                    </a>
                                @else
                                    <span class="text-muted"><i class="ti ti-crown me-1"></i>Tingkat Utama</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Skala Capaian --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-stairs-up text-orange me-2"></i>Skala & Kriteria Capaian</h3>
                </div>
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
                                        <div class="text-reset d-block text-wrap">
                                            {{ $deskripsi }}
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
                    <div class="card-body text-center py-4">
                        <div class="text-muted">Indikator ini tidak memiliki rincian definisi Skala Capaian.</div>
                    </div>
                @endif
            </div>


        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-tags text-blue me-2"></i>Label & Kategori</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($indikator->labels as $label)
                            <span class="badge bg-blue-lt">{{ $label->name }}</span>
                        @empty
                            <span class="text-muted">Tidak ada label.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            @if($indikator->type != 'performa')
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-building text-blue me-2"></i>Unit Kerja & Target</h3>
                    </div>
                    <x-tabler.datatable-client
                        id="table-unit-sidebar"
                        :columns="[
                            ['name' => 'Unit / Lembaga'],
                            ['name' => 'Target']
                        ]"
                    >
                        @forelse($indikator->orgUnits as $unit)
                            <tr>
                                <td data-label="Unit">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-building me-2 text-muted"></i>
                                        <span class="fw-medium">{{ $unit->name }}</span>
                                    </div>
                                </td>
                                <td data-label="Target">
                                    <span class="badge badge-outline text-blue">{{ $unit->pivot->target ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                           {{-- Handled by component or verified empty state --}}
                        @endforelse
                    </x-tabler.datatable-client>

                    @if($indikator->orgUnits->isEmpty())
                        <div class="text-center text-muted py-4">
                            Belum ada unit penanggung jawab yang ditugaskan.
                        </div>
                    @endif
                </div>
            @elseif($indikator->type == 'performa')
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-users-group text-purple me-2"></i>Penugasan Pegawai</h3>
                    </div>
                    <x-tabler.datatable-client
                        id="table-pegawai-kpi-sidebar"
                        :columns="[
                            ['name' => 'Pegawai'],
                            ['name' => 'Bobot/Target']
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
                            <td>
                                <div class="text-muted small">
                                    {{ $ip->weight }}% <br> Tgt: {{ $ip->target_value }}
                                </div>
                            </td>
                        </tr>
                        @empty
                            {{-- Handled by component or verified empty state --}}
                        @endforelse
                    </x-tabler.datatable-client>
                    
                    @if($indikator->pegawai->isEmpty())
                        <div class="text-center text-muted py-4">
                            Belum ada pegawai yang ditugaskan.
                        </div>
                    @endif
                </div>
            @endif

            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-link text-indigo me-2"></i>Referensi Dokumen Terkait</h3>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($indikator->dokSubs as $relSub)
                        @php
                            $tabName = pemutuTabByJenis($relSub->dokumen->jenis) ?? 'kebijakan';
                        @endphp
                        <a href="{{ route('pemutu.dokumens.index', ['tabs' => $tabName]) }}#tree-node-sub-{{ $relSub->encrypted_doksub_id }}" class="list-group-item list-group-item-action d-flex flex-column align-items-start py-3">
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
                        <div class="card-body text-center py-4">
                            <span class="text-muted">Tidak ada referensi dokumen.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endsection
@endif
