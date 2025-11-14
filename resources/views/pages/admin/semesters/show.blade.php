@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Semester Details</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('semesters.edit', $semester->semester_id) }}" class="btn btn-primary">
                <i class="bx bx-edit me-1"></i> Edit
            </a>
            <a href="{{ route('semesters.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <x-flash-message />

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Tahun Ajaran:</h6>
                            <p class="mb-0">{{ $semester->tahun_ajaran }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Semester:</h6>
                            <p class="mb-0">{{ $semester->semester == 1 ? 'Ganjil' : 'Genap' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Start Date:</h6>
                            <p class="mb-0">{{ $semester->start_date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">End Date:</h6>
                            <p class="mb-0">{{ $semester->end_date->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Status:</h6>
                        <p class="mb-0">
                            @if($semester->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <form method="POST" action="{{ route('semesters.destroy', $semester->semester_id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this semester?')">
                                <i class="bx bx-trash me-1"></i> Delete Semester
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
