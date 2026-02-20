<x-tabler.form-modal
    :title="$pegawai->exists ? 'Edit Pegawai' : 'Tambah Pegawai'"
    :route="$pegawai->exists ? route('pemutu.pegawai.update', $pegawai->pegawai_id) : route('pemutu.pegawai.store')"
    :method="$pegawai->exists ? 'PUT' : 'POST'"
    :submitText="$pegawai->exists ? 'Update' : 'Simpan'"
>
    <x-tabler.form-input 
        name="nama" 
        label="Nama Lengkap" 
        type="text" 
        value="{{ old('nama', $pegawai->nama ?? '') }}"
        placeholder="Nama Pegawai" 
        required="true" 
        class="mb-3"
    />
    <x-tabler.form-input 
        name="email" 
        label="Email" 
        type="email" 
        value="{{ old('email', $pegawai->email ?? '') }}"
        placeholder="example@pcr.ac.id" 
        help="Used to link with User account automatically." 
        class="mb-3"
    />
    <x-tabler.form-select 
        name="org_unit_id" 
        label="Unit Organisasi" 
        type="select2" 
        :options="$units->pluck('name', 'orgunit_id')->toArray()"
        :selected="old('org_unit_id', $pegawai->org_unit_id ?? '')" 
        placeholder="Select Unit" 
        class="mb-3"
    />
    <x-tabler.form-select 
        name="jenis" 
        label="Jenis" 
        class="mb-3"
        :options="[ 
            'Dosen' => 'Dosen',
            'Staff' => 'Staff',
            'Mahasiswa' => 'Mahasiswa',
            'Lainnya' => 'Lainnya'
        ]"
        :selected="old('jenis', $pegawai->jenis ?? '')" 
    />
    <x-tabler.form-input 
        name="external_id" 
        label="NIP / ID External" 
        type="text" 
        value="{{ old('external_id', $pegawai->external_id ?? '') }}"
        placeholder="NIP / ID External" 
    />
</x-tabler.form-modal>
