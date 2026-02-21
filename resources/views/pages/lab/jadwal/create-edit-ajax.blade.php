<x-tabler.form-modal
    id_form="{{ $jadwal->exists ? 'editJadwalForm' : 'createJadwalForm' }}"
    title="{{ ($jadwal->exists ? 'Edit ' : 'Tambah ') . 'Jadwal Kuliah' }}"
    route="{{ $jadwal->exists ? route('lab.jadwal.update', $jadwal->encrypted_jadwal_kuliah_id) : route('lab.jadwal.store') }}"
    method="{{ $jadwal->exists ? 'PUT' : 'POST' }}"
>
    <!-- Hidden fields if any -->
    
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-select 
                name="semester_id" 
                label="Semester" 
                :options="$semesters->pluck('tahun_ajaran', 'semester_id')->toArray()" 
                :selected="old('semester_id', $jadwal->semester_id)"
                required
                placeholder="Pilih Semester"
                class="select2-modal"
            />
        </div>
        
        <div class="col-md-12 mb-3">
            <x-tabler.form-select 
                name="mata_kuliah_id" 
                label="Mata Kuliah" 
                :options="$mataKuliahs->pluck('nama_mk', 'mata_kuliah_id')->toArray()" 
                :selected="old('mata_kuliah_id', $jadwal->mata_kuliah_id)"
                required
                placeholder="Pilih Mata Kuliah"
                class="select2-modal"
            />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-select 
                name="hari" 
                label="Hari" 
                :options="['Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu', 'Kamis' => 'Kamis', 'Jumat' => 'Jumat', 'Sabtu' => 'Sabtu', 'Minggu' => 'Minggu']" 
                :selected="old('hari', $jadwal->hari)"
                required
            />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-select 
                name="lab_id" 
                label="Laboratorium" 
                :options="$labs->pluck('name', 'lab_id')->toArray()" 
                :selected="old('lab_id', $jadwal->lab_id)"
                required
                placeholder="Pilih Lab"
            />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                type="time"
                name="jam_mulai" 
                label="Jam Mulai" 
                :value="old('jam_mulai', $jadwal->jam_mulai ? date('H:i', strtotime($jadwal->jam_mulai)) : '')"
                required
            />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                type="time"
                name="jam_selesai" 
                label="Jam Selesai" 
                :value="old('jam_selesai', $jadwal->jam_selesai ? date('H:i', strtotime($jadwal->jam_selesai)) : '')"
                required
            />
        </div>

        <div class="col-md-12 mb-3">
            <x-tabler.form-select 
                name="dosen_id" 
                label="Dosen Pengampu" 
                :options="$dosens->pluck('name', 'id')->toArray()" 
                :selected="old('dosen_id', $jadwal->dosen_id)"
                required
                placeholder="Pilih Dosen"
                class="select2-modal"
            />
        </div>
    </div>
</x-tabler.form-modal>

<script>
    $(document).ready(function() {
        if (typeof window.initOfflineSelect2 === 'function') {
            window.initOfflineSelect2('.select2-modal');
        }
    });
</script>
