<x-tabler.form-modal
    id_form="{{ $mahasiswa->exists ? 'editMahasiswaForm' : 'createMahasiswaForm' }}"
    title="{{ $mahasiswa->exists ? 'Update Data Mahasiswa' : 'Tambah Data Mahasiswa' }}"
    route="{{ $mahasiswa->exists ? route('lab.mahasiswa.update', $mahasiswa->encrypted_mahasiswa_id) : route('lab.mahasiswa.store') }}"
    method="{{ $mahasiswa->exists ? 'PUT' : 'POST' }}"
>
    <div class="mb-3">
        <x-tabler.form-input 
            name="nim" 
            label="NIM" 
            value="{{ old('nim', $mahasiswa->nim) }}"
            placeholder="Nomor Induk Mahasiswa" 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            name="nama" 
            label="Nama Lengkap" 
            value="{{ old('nama', $mahasiswa->nama) }}"
            placeholder="Masukkan nama lengkap..." 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            type="email" 
            name="email" 
            label="Email" 
            value="{{ old('email', $mahasiswa->email) }}"
            placeholder="Email mahasiswa" 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-select 
            name="orgunit_id" 
            label="Program Studi" 
            :options="$prodiList->mapWithKeys(fn($p) => [$p->orgunit_id => $p->name])->toArray()" 
            selected="{{ $mahasiswa->orgunit_id }}"
            placeholder="-- Pilih Program Studi --" 
            required 
        />
    </div>
</x-tabler.form-modal>
