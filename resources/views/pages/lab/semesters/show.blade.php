@if(request()->ajax() || request()->has('ajax'))
    <div class="modal-header">
        <h5 class="modal-title">Semester Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
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
                <p class="mb-0">{{ $semester->start_date }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">End Date:</h6>
                <p class="mb-0">{{ $semester->end_date }}</p>
            </div>
        </div>

        <div class="mb-3">
            <h6 class="text-muted">Status:</h6>
            <p class="mb-0">
                @if($semester->is_active)
                    <span class="badge bg-success text-white">Aktif</span>
                @else
                    <span class="badge bg-secondary text-white">Tidak Aktif</span>
                @endif
            </p>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tutup</button>
        <x-tabler.button :href="route('lab.semesters.edit', $semester->encrypted_semester_id)" class="btn-primary" icon="bx bx-edit" text="Edit" />
    </div>
@else
    @extends('layouts.tabler.app')

    @section('content')
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
            <h4 class="fw-bold py-3 mb-0">Semester Details</h4>
            <div class="d-flex gap-2">
                <x-tabler.button :href="route('lab.semesters.edit', $semester->encrypted_semester_id)" class="btn-primary" icon="bx bx-edit" text="Edit" />
                <x-tabler.button :href="route('lab.semesters.index')" class="btn-secondary" icon="bx bx-arrow-back" text="Back to List" />
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <x-tabler.flash-message />

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
                                <p class="mb-0">{{ $semester->start_date }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">End Date:</h6>
                                <p class="mb-0">{{ $semester->end_date }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted">Status:</h6>
                            <p class="mb-0">
                                @if($semester->is_active)
                                    <span class="badge bg-success text-white">Aktif</span>
                                @else
                                    <span class="badge bg-secondary text-white">Tidak Aktif</span>
                                @endif
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <form method="POST" action="{{ route('lab.semesters.destroy', $semester->encrypted_semester_id) }}">
                                @csrf
                                @method('DELETE')
                                <x-tabler.button type="submit" class="btn-danger" icon="bx bx-trash" text="Delete Semester" onclick="return confirm('Are you sure you want to delete this semester?')" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif
