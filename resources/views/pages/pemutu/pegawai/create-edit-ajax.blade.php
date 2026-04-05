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
        name="nip"
        label="NIP"
        type="text"
        value="{{ old('nip', $pegawai->nip ?? '') }}"
        placeholder="NIP Pegawai"
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
        name="orgunit_departemen_id"
        label="Unit Organisasi"
        type="select2"
        :options="$units->pluck('name', 'orgunit_id')->toArray()"
        :selected="old('orgunit_departemen_id', $pegawai->orgunit_departemen_id ?? '')"
        placeholder="Select Unit"
        class="mb-3"
    />
</x-tabler.form-modal>
