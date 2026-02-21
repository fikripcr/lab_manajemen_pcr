@php
    $isEdit = $perizinan->exists;
    $title  = $isEdit ? 'Edit Perizinan' : 'Tambah Perizinan';
    $route  = $isEdit 
        ? route('hr.perizinan.update', $perizinan->encrypted_perizinan_id) 
        : route('hr.perizinan.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Update' : 'Simpan'"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-select name="pengusul" label="Pengusul" required="true">
                <option value="">Pilih Pengusul</option>
                @foreach($pegawais as $pegawai)
                    <option value="{{ $pegawai->pegawai_id }}" 
                        {{ (old('pengusul', $perizinan->pengusul) == $pegawai->pegawai_id) ? 'selected' : '' }}>
                        {{ $pegawai->latestDataDiri?->nama }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>

        <div class="col-md-12 mb-3">
            <x-tabler.form-select name="jenisizin_id" label="Jenis Izin" required="true">
                <option value="">Pilih Jenis Izin</option>
                @foreach($jenisIzin as $izin)
                    <option value="{{ $izin->jenisizin_id }}" 
                        {{ (old('jenisizin_id', $perizinan->jenisizin_id) == $izin->jenisizin_id) ? 'selected' : '' }}>
                        {{ $izin->nama }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_awal" label="Tanggal Mulai" :value="old('tgl_awal', $perizinan->tgl_awal?->format('Y-m-d'))" required />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_akhir" label="Tanggal Selesai" :value="old('tgl_akhir', $perizinan->tgl_akhir?->format('Y-m-d'))" required />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="time" name="jam_awal" label="Jam Mulai" :value="old('jam_awal', $perizinan->jam_awal)" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="time" name="jam_akhir" label="Jam Selesai" :value="old('jam_akhir', $perizinan->jam_akhir)" />
        </div>

        <div class="col-md-12 mb-3">
            <x-tabler.form-textarea name="pekerjaan_ditinggalkan" label="Pekerjaan yang Ditinggalkan" rows="2" :value="old('pekerjaan_ditinggalkan', $perizinan->pekerjaan_ditinggalkan)" />
        </div>

        <div class="col-md-12 mb-3">
            <x-tabler.form-textarea name="keterangan" label="Keterangan / Alasan" rows="2" :value="old('keterangan', $perizinan->keterangan)" />
        </div>

        <div class="col-md-12 mb-3">
            <x-tabler.form-textarea name="alamat_izin" label="Alamat Selama Izin" rows="2" :value="old('alamat_izin', $perizinan->alamat_izin)" />
        </div>
    </div>
</x-tabler.form-modal>
