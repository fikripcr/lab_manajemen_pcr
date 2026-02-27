@php($isEdit = isset($periode))
<x-tabler.form-modal
    title="{{ $isEdit ? 'Edit Periode Pemesanan' : 'Tambah Periode Pemesanan' }}"
    route="{{ $isEdit ? route('eoffice.jenis-layanan.periode.update', [$jenisLayanan->hashid, $periode->hashid]) : route('eoffice.jenis-layanan.periode.store', $jenisLayanan->hashid) }}"
    method="{{ $isEdit ? 'PUT' : 'POST' }}"
>
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tgl_mulai" label="Tanggal Mulai" value="{{ $periode->tgl_mulai->format('Y-m-d') ?? '' }}" required="true" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tgl_selesai" label="Tanggal Selesai" value="{{ $periode->tgl_selesai->format('Y-m-d') ?? '' }}" required="true" />
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <x-tabler.form-input name="tahun_ajaran" label="Tahun Ajaran" placeholder="Misal: 2023/2024" value="{{ $periode->tahun_ajaran ?? '' }}" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-select name="semester" label="Semester">
                <option value="">Pilih Semester...</option>
                <option value="Ganjil" {{ ($periode->semester ?? '') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ ($periode->semester ?? '') == 'Genap' ? 'selected' : '' }}>Genap</option>
                <option value="Antara" {{ ($periode->semester ?? '') == 'Antara' ? 'selected' : '' }}>Antara</option>
            </x-tabler.form-select>
        </div>
    </div>
</x-tabler.form-modal>
