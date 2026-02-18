<x-tabler.form-modal
    title="Tambah Jadwal Ujian"
    route="{{ route('cbt.jadwal.store') }}"
    method="POST"
    data-redirect="true"
    submitText="Simpan"
>
    <x-tabler.form-input name="nama_kegiatan" label="Nama Kegiatan" placeholder="Contoh: Ujian Masuk Gelombang 1" required="true" />
    
    <x-tabler.form-select name="paket_id" label="Paket Ujian" required="true">
        @foreach($paket as $p)
            <option value="{{ $p->hashid }}">{{ $p->nama_paket }} ({{ $p->tipe_paket }})</option>
        @endforeach
    </x-tabler.form-select>

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input name="waktu_mulai" label="Waktu Mulai" type="datetime-local" required="true" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="waktu_selesai" label="Waktu Selesai" type="datetime-local" required="true" />
        </div>
    </div>
</x-tabler.form-modal>
