@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Ubah Semester" pretitle="Perkuliahan">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.semesters.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form method="POST" action="{{ route('lab.semesters.update', $semester->encrypted_semester_id) }}" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-tabler.form-input name="tahun_ajaran" label="Tahun Ajaran" value="{{ old('tahun_ajaran', $semester->tahun_ajaran) }}" placeholder="e.g. 2023/2024" required="true" />
                                @error('tahun_ajaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-tabler.form-select id="semester" name="semester" label="Semester" required="true">
                                    <option value="">Pilih Semester</option>
                                    <option value="1" {{ old('semester', $semester->semester) == 1 ? 'selected' : '' }}>Ganjil</option>
                                    <option value="2" {{ old('semester', $semester->semester) == 2 ? 'selected' : '' }}>Genap</option>
                                </x-tabler.form-select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-tabler.form-input type="date" name="start_date" id="start_date" label="Start Date" value="{{ old('start_date', $semester->start_date) }}" required="true" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-tabler.form-input type="date" name="end_date" id="end_date" label="End Date" value="{{ old('end_date', $semester->end_date) }}" required="true" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <x-tabler.form-checkbox 
                                name="is_active" 
                                label="Set as Active Semester" 
                                value="1" 
                                :checked="old('is_active', $semester->is_active)" 
                                switch 
                            />
                        </div>

                        <div class="mt-4">
                            <x-tabler.button type="submit" text="Update Semester" />
                            <x-tabler.button type="cancel" :href="route('lab.semesters.index')" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
