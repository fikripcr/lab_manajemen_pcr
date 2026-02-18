<x-tabler.form-modal
    title="Edit Mata Uji"
    route="{{ route('cbt.mata-uji.update', $mu->hashid) }}"
    method="PUT"
    data-redirect="true"
    submitText="Update"
>
    <x-tabler.form-input name="nama_mata_uji" label="Nama Mata Uji" value="{{ $mu->nama_mata_uji }}" required="true" />
    <x-tabler.form-select name="tipe" label="Tipe" required="true" 
        :options="['PMB' => 'PMB (Penerimaan Mahasiswa Baru)', 'Akademik' => 'Akademik (UTS/UAS)']" 
        :selected="$mu->tipe"
        placeholder="Pilih Tipe" />
    <x-tabler.form-textarea name="deskripsi" label="Deskripsi" value="{{ $mu->deskripsi }}" />
</x-tabler.form-modal>
