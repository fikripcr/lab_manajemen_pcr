@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Update KPI Realization
                </h2>
                <div class="text-muted mt-1">{{ $kpi->indikator->indikator }}</div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('pemutu.mykpi.update', $kpi->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Indicator</label>
                        <div class="form-control-plaintext">{{ $kpi->indikator->indikator }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Target</label>
                        <div class="form-control-plaintext">{{ $kpi->target_value ?? $kpi->indikator->target }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Realization / Capaian</label>
                        <textarea name="realization" class="form-control" rows="3" placeholder="Describe your achievement...">{{ old('realization', $kpi->realization) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Score (0-100)</label>
                        <input type="number" name="score" class="form-control" min="0" max="100" step="0.01" value="{{ old('score', $kpi->score) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Evidence / Bukti Dukung (Optional)</label>
                        <input type="file" name="attachment" class="form-control">
                        @if($kpi->attachment)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $kpi->attachment) }}" target="_blank">View Current Attachment</a>
                            </div>
                        @endif
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">Save & Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
