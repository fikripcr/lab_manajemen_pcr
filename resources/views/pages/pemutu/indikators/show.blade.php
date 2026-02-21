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
                            ['name' => 'Target']
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
            @if($indikator->type == 'performa')
                <x-tabler.button href="{{ route('pemutu.kpi.assign', $indikator->encrypted_indikator_id) }}" icon="ti ti-users" text="Tugaskan Pegawai" class="btn-purple" />
            @endif
            <x-tabler.button href="{{ route('pemutu.indikators.edit', $indikator->encrypted_indikator_id) }}" icon="ti ti-pencil" text="Edit Indikator" class="btn-primary" />
        </x-slot:actions>
    </x-tabler.page-header>
    @endsection

    @section('content')
    <div class="row row-cards">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Informasi Indikator</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Pernyataan Standar</label>
                        <div class="form-control-plaintext fs-3 fw-bold">{{ $indikator->indikator }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">No. Indikator</label>
                            <div class="form-control-plaintext">{{ $indikator->no_indikator ?? '-' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Tipe / Jenis</label>
                            <div class="form-control-plaintext">
                                @if($indikator->type == 'renop')
                                    <span class="badge bg-green-lt">Indikator Renop</span>
                                @elseif($indikator->type == 'standar')
                                    <span class="badge bg-blue-lt">Indikator Standar</span>
                                @elseif($indikator->type == 'performa')
                                    <span class="badge bg-purple-lt">Indikator Performa (KPI)</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted">Indikator Induk</label>
                            <div class="form-control-plaintext">
                                @if($indikator->parent)
                                    <a href="{{ route('pemutu.indikators.show', $indikator->parent->encrypted_indikator_id) }}">
                                        [{{ $indikator->parent->no_indikator }}] {{ \Str::limit($indikator->parent->indikator, 150) }}
                                    </a>
                                @else
                                    <span class="text-muted">Tingkat Utama (Top Level)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($indikator->type == 'performa')
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Penugasan Pegawai (KPI)</h3>
                </div>
                <x-tabler.datatable-client
                    id="table-pegawai-kpi"
                    :columns="[
                        ['name' => 'Pegawai'],
                        ['name' => 'Periode'],
                        ['name' => 'Bobot'],
                        ['name' => 'Target Value']
                    ]"
                >
                    @forelse($indikator->pegawai as $ip)
                    <tr>
                        <td>
                            <div class="d-flex py-1 align-items-center">
                                <span class="avatar me-2" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($ip->pegawai->nama) }})"></span>
                                <div class="flex-fill">
                                    <div class="font-weight-medium">{{ $ip->pegawai->nama }}</div>
                                    <div class="text-muted small">{{ $ip->pegawai->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $ip->year }} - {{ $ip->semester }}</td>
                        <td>{{ $ip->weight }}%</td>
                        <td>{{ $ip->target_value }}</td>
                    </tr>
                    @empty
                        {{-- Handled by component or verified empty state --}}
                    @endforelse
                </x-tabler.datatable-client>
                
                @if($indikator->pegawai->isEmpty())
                    <div class="text-center text-muted py-3">Belum ada pegawai yang ditugaskan untuk sasaran kinerja ini.</div>
                @endif
            </div>
            @endif

            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Referensi Dokumen Terkait</h3>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($indikator->dokSubs as $relSub)
                        <a href="{{ route('pemutu.dok-subs.show', $relSub->encrypted_doksub_id) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div>
                                <span class="badge bg-purple-lt me-2">{{ $relSub->dokumen->jenis }}</span>
                                {{ $relSub->dokumen->judul }}
                                <div class="text-muted small mt-1">
                                    <i class="ti ti-corner-down-right me-1"></i>
                                    {{ $relSub->judul }}
                                </div>
                            </div>
                            <i class="ti ti-chevron-right ms-auto"></i>
                        </a>
                    @empty
                        <div class="list-group-item text-center text-muted">Tidak ada referensi dokumen terkait.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Ikhtisar</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5">Dokumen Terkait</dt>
                        <dd class="col-7">{{ $indikator->dokSubs->count() }} Poin</dd>
                        
                        <dt class="col-5">Kategori</dt>
                        <dd class="col-7">{{ $indikator->labels->count() }} Label</dd>
                    </dl>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Label & Kategori</h3>
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Unit Kerja & Target</h3>
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
        </div>
    </div>
    @endsection
@endif
