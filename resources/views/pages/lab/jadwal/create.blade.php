@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Tambah Jadwal" pretitle="Jadwal Kuliah">
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

                    <form method="POST" action="{{ route('jadwal.store') }}" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label for="semester_id" class="col-sm-3 col-form-label required">Semester</label>
                            <div class="col-sm-9">
                                <x-form.select2 
                                    id="semester_id" 
                                    name="semester_id" 
                                    placeholder="Pilih Semester" 
                                    :options="$semesters->mapWithKeys(fn($s) => [$s->semester_id => $s->tahun_ajaran . ' - ' . ($s->semester == 1 ? 'Ganjil' : 'Genap')])->toArray()"
                                    :selected="old('semester_id')" 
                                    required="true"
                                />
                                @error('semester_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="mata_kuliah_id" class="col-sm-3 col-form-label required">Mata Kuliah</label>
                            <div class="col-sm-9">
                                <x-form.select2 
                                    id="mata_kuliah_id" 
                                    name="mata_kuliah_id" 
                                    placeholder="Pilih Mata Kuliah" 
                                    :options="$mataKuliahs->mapWithKeys(fn($mk) => [$mk->id => $mk->kode_mk . ' - ' . $mk->nama_mk])->toArray()"
                                    :selected="old('mata_kuliah_id')" 
                                    required="true"
                                />
                                @error('mata_kuliah_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="dosen_id" class="col-sm-3 col-form-label required">Dosen</label>
                            <div class="col-sm-9">
                                <x-form.select2 
                                    id="dosen_id" 
                                    name="dosen_id" 
                                    placeholder="Pilih Dosen" 
                                    :options="$dosens->pluck('name', 'id')->toArray()"
                                    :selected="old('dosen_id')" 
                                    required="true"
                                />
                                @error('dosen_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="lab_id" class="col-sm-3 col-form-label required">Lab</label>
                            <div class="col-sm-9">
                                <x-form.select2 
                                    id="lab_id" 
                                    name="lab_id" 
                                    placeholder="Pilih Lab" 
                                    :options="$labs->pluck('name', 'lab_id')->toArray()"
                                    :selected="old('lab_id')" 
                                    required="true"
                                />
                                @error('lab_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="hari" class="col-sm-3 col-form-label required">Hari</label>
                            <div class="col-sm-9">
                                <x-form.select2 
                                    id="hari" 
                                    name="hari" 
                                    placeholder="Pilih Hari" 
                                    :options="array_combine(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'], ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])"
                                    :selected="old('hari')" 
                                    required="true"
                                />
                                @error('hari')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="jam_mulai" class="col-sm-3 col-form-label required">Waktu</label>
                            <div class="col-sm-9">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                                    </div>
                                    <div class="col-auto pt-2">sampai</div>
                                    <div class="col-auto">
                                        <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                                    </div>
                                </div>
                                @error('jam_mulai') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                @error('jam_selesai') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-9 offset-sm-3">
                                <x-tabler.button type="submit" text="Save Jadwal" />
                                <x-tabler.button type="cancel" :href="route('jadwal.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

                        <div class="row mb-3">
                            <label for="semester_id" class="col-sm-3 col-form-label required">Semester</label>
                            <div class="col-sm-9">
                                <select class="form-select @error('semester_id') is-invalid @enderror" id="semester_id" name="semester_id" required>
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

                        <div class="row mb-3">
                            <label for="mata_kuliah_id" class="col-sm-3 col-form-label required">Mata Kuliah</label>
                            <div class="col-sm-9">
                                <select class="form-select @error('mata_kuliah_id') is-invalid @enderror" id="mata_kuliah_id" name="mata_kuliah_id" required>
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

                        <div class="row mb-3">
                            <label for="dosen_id" class="col-sm-3 col-form-label required">Dosen</label>
                            <div class="col-sm-9">
                                <select class="form-select @error('dosen_id') is-invalid @enderror" id="dosen_id" name="dosen_id" required>
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

                        <div class="row mb-3">
                            <label for="lab_id" class="col-sm-3 col-form-label required">Lab</label>
                            <div class="col-sm-9">
                                <select class="form-select @error('lab_id') is-invalid @enderror" id="lab_id" name="lab_id" required>
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

                        <div class="row mb-3">
                            <label for="hari" class="col-sm-3 col-form-label required">Hari</label>
                            <div class="col-sm-9">
                                <select class="form-select @error('hari') is-invalid @enderror" id="hari" name="hari" required>
                                    <option value="">Pilih Hari</option>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                        <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                                    @endforeach
                                </select>
                                @error('hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="jam_mulai" class="col-sm-3 col-form-label required">Waktu</label>
                            <div class="col-sm-9">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                                    </div>
                                    <div class="col-auto pt-2">sampai</div>
                                    <div class="col-auto">
                                        <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                                    </div>
                                </div>
                                @error('jam_mulai') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                @error('jam_selesai') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-9 offset-sm-3">
                                <x-tabler.button type="submit" text="Save Jadwal" />
                                <x-tabler.button type="cancel" :href="route('jadwal.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
