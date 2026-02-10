@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Edit Semester" pretitle="Master Data">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('semesters.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form method="POST" action="{{ route('semesters.update', $semester->encrypted_semester_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tahun_ajaran" class="form-label fw-bold">Tahun Ajaran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror"
                                       id="tahun_ajaran" name="tahun_ajaran"
                                       value="{{ old('tahun_ajaran', $semester->tahun_ajaran) }}"
                                       placeholder="e.g. 2023/2024" >
                                @error('tahun_ajaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="semester" class="form-label fw-bold">Semester <span class="text-danger">*</span></label>
                                <select class="form-select @error('semester') is-invalid @enderror"
                                        id="semester" name="semester" >
                                    <option value="">Pilih Semester</option>
                                    <option value="1" {{ old('semester', $semester->semester) == 1 ? 'selected' : '' }}>Ganjil</option>
                                    <option value="2" {{ old('semester', $semester->semester) == 2 ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                       id="start_date" name="start_date"
                                       value="{{ old('start_date', $semester->start_date) }}" >
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label fw-bold">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                       id="end_date" name="end_date"
                                       value="{{ old('end_date', $semester->end_date) }}" >
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $semester->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Set as Active Semester
                                </label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update
                            </button>
                            <a href="{{ route('semesters.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
