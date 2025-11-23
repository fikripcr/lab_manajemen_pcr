@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0">Mata Kuliah Details</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('mata-kuliah.edit', $mataKuliah->encrypted_mata_kuliah_id) }}" class="btn btn-primary">
                <i class="bx bx-edit me-1"></i> Edit
            </a>
            <a href="{{ route('mata-kuliah.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <x-admin.flash-message />

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Kode MK:</h6>
                            <p class="mb-0">{{ $mataKuliah->kode_mk }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">SKS:</h6>
                            <p class="mb-0">{{ $mataKuliah->sks }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Nama MK:</h6>
                        <p class="mb-0">{{ $mataKuliah->nama_mk }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <form method="POST" action="{{ route('mata-kuliah.destroy', $mataKuliah->encrypted_mata_kuliah_id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this mata kuliah?')">
                                <i class="bx bx-trash me-1"></i> Delete Mata Kuliah
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
