@if(request()->ajax() || request()->has('ajax'))
    <x-tabler.form-modal
        title="Edit Jadwal"
        route="{{ route('lab.jadwal.update', $jadwal->encrypt_id ?? $jadwal->id) }}"
        method="PUT"
        submitText="Update Jadwal"
    >
        <div class="mb-3">
            <x-tabler.form-select name="semester_id" label="Semester" :options="$semesters->mapWithKeys(fn($s) => [$s->semester_id => $s->tahun_ajaran . ' - ' . ($s->semester == 1 ? 'Ganjil' : 'Genap')])->toArray()" selected="{{ old('semester_id', $jadwal->semester_id) }}" placeholder="Pilih Semester" required />
        </div>

        <div class="mb-3">
            <x-tabler.form-select name="mata_kuliah_id" label="Mata Kuliah" :options="$mataKuliahs->mapWithKeys(fn($mk) => [$mk->id => $mk->kode_mk . ' - ' . $mk->nama_mk])->toArray()" selected="{{ old('mata_kuliah_id', $jadwal->mata_kuliah_id) }}" placeholder="Pilih Mata Kuliah" required />
        </div>

        <div class="mb-3">
            <x-tabler.form-select name="dosen_id" label="Dosen" :options="$dosens->pluck('name', 'id')->toArray()" selected="{{ old('dosen_id', $jadwal->dosen_id) }}" placeholder="Pilih Dosen" required />
        </div>

        <div class="mb-3">
            <x-tabler.form-select name="lab_id" label="Lab" :options="$labs->pluck('name', 'lab_id')->toArray()" selected="{{ old('lab_id', $jadwal->lab_id) }}" placeholder="Pilih Lab" required />
        </div>

        <div class="mb-3">
            <x-tabler.form-select name="hari" label="Hari" :options="array_combine(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'], ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])" selected="{{ old('hari', $jadwal->hari) }}" placeholder="Pilih Hari" required />
        </div>

        <div class="mb-3">
            <label class="form-label required">Waktu</label>
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <x-tabler.form-input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required />
                </div>
                <div class="col-auto">sampai</div>
                <div class="col-auto">
                    <x-tabler.form-input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" required />
                </div>
            </div>
        </div>
    </x-tabler.form-modal>
@else
    @extends('layouts.tabler.app')

    @section('header')
        <x-tabler.page-header title="Edit Jadwal" pretitle="Jadwal Kuliah">
            <x-slot:actions>
                <x-tabler.button type="back" :href="route('lab.jadwal.index')" />
            </x-slot:actions>
        </x-tabler.page-header>
    @endsection

    @section('content')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <x-tabler.flash-message />

                        <form method="POST" action="{{ route('lab.jadwal.update', $jadwal->encrypt_id ?? $jadwal->id) }}" class="ajax-form">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <x-tabler.form-select name="semester_id" label="Semester" :options="$semesters->mapWithKeys(fn($s) => [$s->semester_id => $s->tahun_ajaran . ' - ' . ($s->semester == 1 ? 'Ganjil' : 'Genap')])->toArray()" selected="{{ old('semester_id', $jadwal->semester_id) }}" placeholder="Pilih Semester" required />
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-select name="mata_kuliah_id" label="Mata Kuliah" :options="$mataKuliahs->mapWithKeys(fn($mk) => [$mk->id => $mk->kode_mk . ' - ' . $mk->nama_mk])->toArray()" selected="{{ old('mata_kuliah_id', $jadwal->mata_kuliah_id) }}" placeholder="Pilih Mata Kuliah" required />
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-select name="dosen_id" label="Dosen" :options="$dosens->pluck('name', 'id')->toArray()" selected="{{ old('dosen_id', $jadwal->dosen_id) }}" placeholder="Pilih Dosen" required />
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-select name="lab_id" label="Lab" :options="$labs->pluck('name', 'lab_id')->toArray()" selected="{{ old('lab_id', $jadwal->lab_id) }}" placeholder="Pilih Lab" required />
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-select name="hari" label="Hari" :options="array_combine(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'], ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])" selected="{{ old('hari', $jadwal->hari) }}" placeholder="Pilih Hari" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Waktu</label>
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <x-tabler.form-input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required />
                                    </div>
                                    <div class="col-auto">sampai</div>
                                    <div class="col-auto">
                                        <x-tabler.form-input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" required />
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-tabler.button type="submit" text="Update Jadwal" />
                                <x-tabler.button type="cancel" :href="route('lab.jadwal.index')" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif
