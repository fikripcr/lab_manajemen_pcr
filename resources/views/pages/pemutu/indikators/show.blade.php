@extends('layouts.admin.app')
@section('title', 'Detail Indikator')

@section('header')
<x-tabler.page-header title="Detail Indikator" pretitle="SPMI" :back-url="route('pemutu.dok-subs.show', $indikator->doksub_id)">
    <x-slot:actions>
        <x-tabler.button type="a" href="{{ route('pemutu.indikators.edit', $indikator->indikator_id) }}" icon="ti ti-pencil" text="Edit Indikator" class="btn-primary" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Indicator Information</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Statement</label>
                    <div class="form-control-plaintext fs-3 fw-bold">{{ $indikator->indikator }}</div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">No. Indikator</label>
                        <div class="form-control-plaintext">{{ $indikator->no_indikator ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Type</label>
                        <div class="form-control-plaintext">{{ $indikator->jenis_indikator ?? '-' }}</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">Target</label>
                        <div class="form-control-plaintext">{{ $indikator->target ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cross-References (Related Documents)</h3>
            </div>
            <div class="list-group list-group-flush">
                @forelse($indikator->relatedDokSubs as $relSub)
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
                    <div class="list-group-item text-center text-muted">No related documents linked.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Context</h3>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">Document</dt>
                    <dd class="col-7">{{ $indikator->dokSub->dokumen->judul }}</dd>
                    
                    <dt class="col-5">Sub-Doc</dt>
                    <dd class="col-7">{{ $indikator->dokSub->judul }}</dd>

                    <dt class="col-5">Period</dt>
                    <dd class="col-7">{{ $indikator->dokSub->dokumen->periode }}</dd>
                </dl>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Labels / Tags</h3>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @forelse($indikator->labels as $label)
                        <span class="badge bg-blue-lt">{{ $label->name }}</span>
                    @empty
                        <span class="text-muted">No labels assigned.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Assigned Org Units</h3>
            </div>
            <div class="list-group list-group-flush">
                @forelse($indikator->orgUnits as $unit)
                    <div class="list-group-item">
                        <i class="ti ti-building me-2 text-muted"></i>
                        {{ $unit->name }}
                    </div>
                @empty
                    <div class="list-group-item text-muted">No units assigned.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
