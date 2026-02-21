@php
    $isEdit = $pengembangan->exists;
    $title  = $isEdit ? 'Edit Riwayat Pengembangan' : 'Tambah Riwayat Pengembangan';
    $route  = $isEdit 
        ? route('hr.pegawai.pengembangan.update', [$pegawai->encrypted_pegawai_id, $pengembangan->pengembangandiri_id]) 
        : route('hr.pegawai.pengembangan.store', $pegawai->encrypted_pegawai_id);
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Simpan Perubahan' : 'Simpan Pengajuan'"
    submitIcon="ti ti-device-floppy"
>
    <div class="alert alert-info">
        <i class="ti ti-info-circle me-2"></i>
        {{ $isEdit ? 'Perubahan data' : 'Data yang Anda tambahkan' }} akan berstatus <strong>Menunggu Persetujuan</strong>.
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-select name="jenis_kegiatan" label="Jenis Kegiatan" required="true">
                <option value="">Pilih Jenis Kegiatan</option>
                @foreach(['Pelatihan', 'Seminar', 'Workshop', 'Sertifikasi', 'Lainnya'] as $jenis)
                    <option value="{{ $jenis }}" {{ $pengembangan->jenis_kegiatan == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                @endforeach
            </x-tabler.form-select>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="nama_kegiatan" label="Nama Kegiatan" :value="$pengembangan->nama_kegiatan" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="nama_penyelenggara" label="Penyelenggara" :value="$pengembangan->nama_penyelenggara" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="peran" label="Peran" :value="$pengembangan->peran" placeholder="Contoh: Peserta, Narasumber" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="tgl_mulai" label="Tanggal Mulai" type="date" :value="$pengembangan->tgl_mulai ? \Carbon\Carbon::parse($pengembangan->tgl_mulai)->format('Y-m-d') : ''" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="tgl_selesai" label="Tanggal Selesai" type="date" :value="$pengembangan->tgl_selesai ? \Carbon\Carbon::parse($pengembangan->tgl_selesai)->format('Y-m-d') : ''" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="tahun" label="Tahun" type="number" :value="$pengembangan->tahun" placeholder="YYYY" required="true" />
        </div>

        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="3" :value="$pengembangan->keterangan" />
    </div>
</x-tabler.form-modal>
