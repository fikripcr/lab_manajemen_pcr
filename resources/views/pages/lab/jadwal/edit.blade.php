@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Edit Jadwal" pretitle="Jadwal Kuliah">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('jadwal.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form method="POST" action="{{ route('jadwal.update', $jadwal->encrypt_id) }}" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required" for="semester_id">Semester</label>
                            <div class="col-sm-9">
                                <x-tabler.form-select name="semester_id" :options="$semesters->mapWithKeys(fn($s) => [$s->semester_id => $s->tahun_ajaran . ' - ' . ($s->semester == 1 ? 'Ganjil' : 'Genap')])->toArray()" selected="{{ old('semester_id', $jadwal->semester_id) }}" placeholder="Pilih Semester" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required" for="mata_kuliah_id">Mata Kuliah</label>
                            <div class="col-sm-9">
                                <x-tabler.form-select name="mata_kuliah_id" :options="$mataKuliahs->mapWithKeys(fn($mk) => [$mk->id => $mk->kode_mk . ' - ' . $mk->nama_mk])->toArray()" selected="{{ old('mata_kuliah_id', $jadwal->mata_kuliah_id) }}" placeholder="Pilih Mata Kuliah" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required" for="dosen_id">Dosen</label>
                            <div class="col-sm-9">
                                <x-tabler.form-select name="dosen_id" :options="$dosens->pluck('name', 'id')->toArray()" selected="{{ old('dosen_id', $jadwal->dosen_id) }}" placeholder="Pilih Dosen" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required" for="lab_id">Lab</label>
                            <div class="col-sm-9">
                                <x-tabler.form-select name="lab_id" :options="$labs->pluck('name', 'lab_id')->toArray()" selected="{{ old('lab_id', $jadwal->lab_id) }}" placeholder="Pilih Lab" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required" for="hari">Hari</label>
                            <div class="col-sm-9">
                                <x-tabler.form-select name="hari" :options="array_combine(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'], ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])" selected="{{ old('hari', $jadwal->hari) }}" placeholder="Pilih Hari" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">Waktu</label>
                            <div class="col-sm-9">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <x-tabler.form-input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required class="mb-0" />
                                    </div>
                                    <div class="col-auto pt-2">sampai</div>
                                    <div class="col-auto">
                                        <x-tabler.form-input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" required class="mb-0" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-9 offset-sm-3">
                                <x-tabler.button type="submit" text="Update Jadwal" />
                                <x-tabler.button type="cancel" :href="route('jadwal.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
