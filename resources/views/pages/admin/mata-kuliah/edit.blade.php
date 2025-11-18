@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Edit Mata Kuliah</h4>
        <a href="{{ route('mata-kuliah.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <x-flash-message />

                    <form method="POST" action="{{ route('mata-kuliah.update', $mataKuliah->encrypted_mata_kuliah_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_mk" class="form-label fw-bold">Kode MK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kode_mk') is-invalid @enderror"
                                       id="kode_mk" name="kode_mk"
                                       value="{{ old('kode_mk', $mataKuliah->kode_mk) }}"
                                       placeholder="e.g. IF101" >
                                @error('kode_mk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sks" class="form-label fw-bold">SKS <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('sks') is-invalid @enderror"
                                       id="sks" name="sks"
                                       value="{{ old('sks', $mataKuliah->sks) }}"
                                       min="1" max="6" >
                                @error('sks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nama_mk" class="form-label fw-bold">Nama MK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_mk') is-invalid @enderror"
                                   id="nama_mk" name="nama_mk"
                                   value="{{ old('nama_mk', $mataKuliah->nama_mk) }}"
                                   placeholder="e.g. Pemrograman Web" >
                            @error('nama_mk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update
                            </button>
                            <a href="{{ route('mata-kuliah.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
