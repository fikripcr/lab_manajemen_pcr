<x-tabler.form-modal 
    title="{{ $tamu->exists ? 'Edit Data Tamu' : 'Tambah Data Tamu' }}" 
    action="{{ $tamu->exists ? route('Kegiatan.tamus.update', $tamu->hashid) : route('Kegiatan.tamus.store') }}"
    method="{{ $tamu->exists ? 'PUT' : 'POST' }}"
>
    <div class="row g-3">
        <div class="col-12">
            <x-tabler.form-select name="Kegiatan_id" label="Kegiatan" required="true">
                <option value="">-- Pilih Kegiatan --</option>
                @foreach($Kegiatans as $Kegiatan)
                    <option value="{{ $Kegiatan->Kegiatan_id }}" {{ (isset($active_Kegiatan_id) && $active_Kegiatan_id == $Kegiatan->Kegiatan_id) || $tamu->Kegiatan_id == $Kegiatan->Kegiatan_id ? 'selected' : '' }}>
                        {{ $Kegiatan->judul_Kegiatan }} ({{ formatTanggalIndo($Kegiatan->tanggal_mulai) }})
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>

        <div class="col-md-6">
            <x-tabler.form-input name="nama_tamu" label="Nama Lengkap" value="{{ $tamu->nama_tamu }}" required="true" />
        </div>

        <div class="col-md-6">
            <x-tabler.form-input name="instansi" label="Instansi/Organisasi" value="{{ $tamu->instansi }}" />
        </div>

        <div class="col-md-6">
            <x-tabler.form-input name="jabatan" label="Jabatan" value="{{ $tamu->jabatan }}" />
        </div>

        <div class="col-md-6">
            <x-tabler.form-input name="kontak" label="Kontak (WA/Email)" value="{{ $tamu->kontak }}" />
        </div>

        <div class="col-12">
            <x-tabler.form-input name="tujuan" label="Tujuan Kedatangan" value="{{ $tamu->tujuan }}" />
        </div>

        <div class="col-md-12">
            <x-tabler.form-input name="waktu_datang" label="Waktu Kedatangan" type="datetime-local" value="{{ $tamu->waktu_datang?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i') }}" />
        </div>

        <!-- FilePond Uploads -->
        <div class="col-md-6">
            <div class="form-label">Foto Tamu</div>
            <input type="file" name="foto" class="filepond" data-allow-reorder="true" data-max-file-size="3MB" data-max-files="1">
            @if($tamu->photo_url)
                <div class="mt-2 small text-muted">Foto saat ini: <a href="{{ $tamu->photo_url }}" target="_blank">Lihat</a></div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="form-label">Tanda Tangan</div>
            <input type="file" name="ttd" class="filepond" data-allow-reorder="true" data-max-file-size="3MB" data-max-files="1">
            @if($tamu->signature_url)
                <div class="mt-2 small text-muted">TTD saat ini: <a href="{{ $tamu->signature_url }}" target="_blank">Lihat</a></div>
            @endif
        </div>

        <div class="col-12">
            <x-tabler.form-textarea name="keterangan" label="Keterangan Tambahan">{{ $tamu->keterangan }}</x-tabler.form-textarea>
        </div>
    </div>
</x-tabler.form-modal>

<script>
    // Re-initialize FilePond for items inside modal
    if (typeof initializeFilePond === 'function') {
        initializeFilePond('.filepond');
    }
</script>
