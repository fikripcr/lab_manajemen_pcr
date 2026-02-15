@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Assign Standard Indicator
                </h2>
                <div class="text-muted mt-1">
                    {{ $indikator->indikator }} (Type: {{ ucfirst($indikator->type) }})
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assign to Personnel</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('pemutu.standar.storeAssignment', $indikator->indikator_id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Personnel</label>
                                <select name="personil_id" class="form-select @error('personil_id') is-invalid @enderror" required>
                                    <option value="">Select Personnel...</option>
                                    @foreach($personils as $personil)
                                        <option value="{{ $personil->personil_id }}">{{ $personil->nama }} ({{ $personil->jenis }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Year</label>
                                    <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Semester</label>
                                    <select name="semester" class="form-select" required>
                                        <option value="1">Ganjil</option>
                                        <option value="2">Genap</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Target (Optional Override)</label>
                                <input type="text" name="target_value" class="form-control" placeholder="{{ $indikator->target }}">
                                <small class="form-hint">Leave blank to use default target: {{ $indikator->target }}</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Weight (Bobot)</label>
                                <input type="number" name="weight" class="form-control" step="0.01" value="0">
                            </div>
                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary">Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Current Assignments</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap">
                            <thead>
                                <tr>
                                    <th>Personnel</th>
                                    <th>Period</th>
                                    <th>Target</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assigned as $assign)
                                <tr>
                                    <td>
                                        <div>{{ $assign->personil->nama ?? 'Unknown' }}</div>
                                        <div class="text-muted small">{{ $assign->personil->email ?? '' }}</div>
                                    </td>
                                    <td>{{ $assign->year }} / {{ $assign->semester == 1 ? 'Ganjil' : 'Genap' }}</td>
                                    <td>{{ $assign->target_value ?? $indikator->target }}</td>
                                    <td>
                                        <span class="badge bg-{{ $assign->status == 'approved' ? 'green' : ($assign->status == 'submitted' ? 'blue' : 'secondary') }}">
                                            {{ ucfirst($assign->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('pemutu.standar.destroyAssignment', $assign->id) }}" method="POST" onsubmit="return confirm('Are you sure? This will remove the assignment.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Remove Assignment">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No assignments found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
