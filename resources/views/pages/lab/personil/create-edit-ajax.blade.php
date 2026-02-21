<x-tabler.form-modal
    id_form="{{ $personil->exists ? 'editPersonilForm' : 'createPersonilForm' }}"
    title="{{ $personil->exists ? 'Update Data Personil' : 'Tambah Data Personil' }}"
    route="{{ $personil->exists ? route('lab.personil.update', $personil->encrypted_personil_id) : route('lab.personil.store') }}"
    method="{{ $personil->exists ? 'PUT' : 'POST' }}"
>
    <div class="mb-3">
        <x-tabler.form-input 
            name="nip" 
            label="NIP / NIK" 
            value="{{ old('nip', $personil->nip) }}"
            placeholder="Nomor Induk Pegawai" 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            name="nama" 
            label="Nama Lengkap" 
            value="{{ old('nama', $personil->nama) }}"
            placeholder="Masukkan nama lengkap..." 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            type="email" 
            name="email" 
            label="Email" 
            value="{{ old('email', $personil->email) }}"
            placeholder="Email institusi" 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            name="posisi" 
            label="Posisi / Jabatan" 
            value="{{ old('posisi', $personil->posisi) }}"
            placeholder="Contoh: Laboran, Dosen, IT Support" 
            required 
        />
    </div>
</x-tabler.form-modal>
