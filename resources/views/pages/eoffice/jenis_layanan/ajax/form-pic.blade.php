<x-tabler.form-modal
    title="Tambah PIC Layanan"
    route="{{ route('eoffice.jenis-layanan.store-pic', $layanan->hashid) }}"
    method="POST"
>
    <div class="mb-3">
        <x-tabler.form-select name="user_id" label="Pilih Pegawai" class="select2" required="true">
            <option value="">Cari Pegawai...</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input name="jabatan" label="Jabatan/Peran PIC" placeholder="Misal: Koordinator, Verifikator, dll" required="true" />
    </div>
</x-tabler.form-modal>
