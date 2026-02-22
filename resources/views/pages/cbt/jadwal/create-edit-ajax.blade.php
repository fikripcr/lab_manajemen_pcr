@php
    $isEdit = isset($jadwal) && $jadwal->exists;
    $title  = ($isEdit ? 'Edit' : 'Tambah') . ' Jadwal Ujian';
    $route  = $isEdit ? route('cbt.jadwal.update', $jadwal->encrypted_jadwal_ujian_id) : route('cbt.jadwal.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Update' : 'Simpan'"
>
    <x-tabler.form-input name="nama_kegiatan" label="Nama Kegiatan" placeholder="Contoh: Ujian Masuk Gelombang 1"
        :value="$isEdit ? $jadwal->nama_kegiatan : old('nama_kegiatan')" required="true" />

    <x-tabler.form-select name="paket_id" label="Paket Ujian" required="true">
        @foreach($paket as $p)
            <option value="{{ $p->encrypted_paket_ujian_id }}"
                {{ ($isEdit && $jadwal->paket->encrypted_paket_ujian_id == $p->encrypted_paket_ujian_id) ? 'selected' : '' }}>
                {{ $p->nama_paket }} ({{ $p->tipe_paket }})
            </option>
        @endforeach
    </x-tabler.form-select>

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input name="waktu_mulai" label="Waktu Mulai" type="datetime-local" required="true"
                :value="$isEdit ? $jadwal->waktu_mulai->format('Y-m-d\TH:i') : old('waktu_mulai')" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="waktu_selesai" label="Waktu Selesai" type="datetime-local" required="true"
                :value="$isEdit ? $jadwal->waktu_selesai->format('Y-m-d\TH:i') : old('waktu_selesai')" />
        </div>
    </div>
</x-tabler.form-modal>
