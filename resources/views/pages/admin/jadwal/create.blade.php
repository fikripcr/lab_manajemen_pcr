@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0">Tambah Jadwal</h4>
        <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <x-admin.flash-message />

                    <form method="POST" action="{{ route('jadwal.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="semester_id" class="form-label fw-bold">Semester <span class="text-danger">*</span></label>
                                    <select class="form-control @error('semester_id') is-invalid @enderror" id="semester_id" name="semester_id" >
                                        <option value="">Pilih Semester</option>
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester->semester_id }}" {{ old('semester_id') == $semester->semester_id ? 'selected' : '' }}>
                                                {{ $semester->tahun_ajaran }} - {{ $semester->semester == 1 ? 'Ganjil' : 'Genap' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('semester_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mata_kuliah_id" class="form-label fw-bold">Mata Kuliah <span class="text-danger">*</span></label>
                                    <select class="form-control @error('mata_kuliah_id') is-invalid @enderror" id="mata_kuliah_id" name="mata_kuliah_id" >
                                        <option value="">Pilih Mata Kuliah</option>
                                        @foreach($mataKuliahs as $mataKuliah)
                                            <option value="{{ $mataKuliah->id }}" {{ old('mata_kuliah_id') == $mataKuliah->id ? 'selected' : '' }}>
                                                {{ $mataKuliah->kode_mk }} - {{ $mataKuliah->nama_mk }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mata_kuliah_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dosen_id" class="form-label fw-bold">Dosen <span class="text-danger">*</span></label>
                                    <select class="form-control @error('dosen_id') is-invalid @enderror" id="dosen_id" name="dosen_id" >
                                        <option value="">Pilih Dosen</option>
                                        @foreach($dosens as $dosen)
                                            <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                                {{ $dosen->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dosen_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lab_id" class="form-label fw-bold">Lab <span class="text-danger">*</span></label>
                                    <select class="form-control @error('lab_id') is-invalid @enderror" id="lab_id" name="lab_id" >
                                        <option value="">Pilih Lab</option>
                                        @foreach($labs as $lab)
                                            <option value="{{ $lab->lab_id }}" {{ old('lab_id') == $lab->lab_id ? 'selected' : '' }}>
                                                {{ $lab->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lab_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hari" class="form-label fw-bold">Hari <span class="text-danger">*</span></label>
                                    <select class="form-control @error('hari') is-invalid @enderror" id="hari" name="hari" >
                                        <option value="">Pilih Hari</option>
                                        <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                        <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                        <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                        <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                        <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                        <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                        <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                                    </select>
                                    @error('hari')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="jam_mulai" class="form-label fw-bold">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" >
                                    @error('jam_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="jam_selesai" class="form-label fw-bold">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" >
                                    @error('jam_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Save Jadwal
                            </button>
                            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
