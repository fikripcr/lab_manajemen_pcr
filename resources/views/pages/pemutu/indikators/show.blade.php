@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')
@section('title', 'Detail Indikator')

@section('header')
<x-tabler.page-header title="Detail Indikator" pretitle="SPMI">
    <x-slot:actions>
        <x-tabler.button type="a" href="javascript:history.back()" icon="ti ti-arrow-left" text="Kembali" class="btn-secondary" />
        @if($indikator->type == 'performa')
            <x-tabler.button type="a" href="{{ route('pemutu.kpi.assign', $indikator->indikator_id) }}" icon="ti ti-users" text="Assign Personnel" class="btn-purple" />
        @endif
        <x-tabler.button type="a" href="{{ route('pemutu.indikators.edit', $indikator->indikator_id) }}" icon="ti ti-pencil" text="Edit Indikator" class="btn-primary" />
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
                                <a href="{{ route('pemutu.indikators.show', $indikator->parent_id) }}">
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
                <h3 class="card-title">Penugasan Personel (KPI)</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Pegawai</th>
                            <th>Periode</th>
                            <th>Bobot</th>
                            <th>Target Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($indikator->personils as $ip)
                        <tr>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <span class="avatar me-2" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($ip->personil->nama) }})"></span>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium">{{ $ip->personil->nama }}</div>
                                        <div class="text-muted small">{{ $ip->personil->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $ip->year }} - {{ $ip->semester }}</td>
                            <td>{{ $ip->weight }}%</td>
                            <td>{{ $ip->target_value }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada personel yang ditugaskan untuk sasaran kinerja ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Referensi Dokumen Terkait</h3>
            </div>
            <div class="list-group list-group-flush">
                @forelse($indikator->dokSubs as $relSub)
                    <a href="{{ route('pemutu.dok-subs.show', $relSub->doksub_id) }}" class="list-group-item list-group-item-action d-flex align-items-center">
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
            <div class="table-responsive">
                <table class="table table-vcenter table-mobile-md card-table">
                    <thead>
                        <tr>
                            <th>Unit / Lembaga</th>
                            <th>Target</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <td colspan="2" class="text-center text-muted py-4">
                                    Belum ada unit penanggung jawab yang ditugaskan.
                                </td>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
