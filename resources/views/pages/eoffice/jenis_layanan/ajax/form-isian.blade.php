<x-tabler.form-modal
    title="Tambah Isian Form"
    route="{{ route('eoffice.jenis-layanan.store-isian', $layanan->hashid) }}"
    method="POST"
>
    <div class="mb-3">
        <x-tabler.form-select name="kategori_isian_id" label="Kategori Isian" class="select2" required="true">
            <option value="">Pilih Kategori...</option>
            @foreach($kategoriIsians as $kat)
                <option value="{{ $kat->kategorisian_id }}">{{ $kat->nama_isian }} ({{ $kat->tipe_isian }})</option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input name="label" label="Label Tampilan" placeholder="Label yang akan muncul di form" required="true" />
    </div>
    <div class="mb-3">
        <x-tabler.form-input name="placeholder" label="Placeholder" placeholder="Teks bantuan di dalam input" />
    </div>
</x-tabler.form-modal>
