@php
    $modelExists = isset($layanan) && $layanan->exists;
    $showOn = [];
    if($modelExists) {
        $showOn = is_array($layanan->only_show_on) ? $layanan->only_show_on : ($layanan->only_show_on ? json_decode($layanan->only_show_on, true) : []);
    } else {
        $showOn = ['Pegawai', 'Mahasiswa', 'Dosen'];
    }
@endphp

<x-tabler.form-modal
    title="{{ $modelExists ? 'Ubah Jenis Layanan' : 'Tambah Jenis Layanan' }}"
    route="{{ $modelExists ? route('eoffice.jenis-layanan.update', $layanan->encrypted_jenislayanan_id) : route('eoffice.jenis-layanan.store') }}"
    method="{{ $modelExists ? 'PUT' : 'POST' }}"
    enctype="multipart/form-data"
>
    <x-tabler.form-input name="nama_layanan" label="Nama Layanan" value="{{ $layanan->nama_layanan ?? '' }}" placeholder="Contoh: Surat Keterangan Mahasiswa Aktif" required />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select name="kategori" label="Kategori" required>
                @foreach(['layanan', 'umum', 'akademik', 'keuangan', 'sdm', 'sarpras'] as $cat)
                    <option value="{{ $cat }}" {{ ($layanan->kategori ?? '') == $cat ? 'selected' : '' }}>{{ ucwords($cat) }}</option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="number" name="batas_pengerjaan" label="Batas Pengerjaan (Jam)" value="{{ $layanan->batas_pengerjaan ?? '24' }}" required />
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Tampilkan Untuk</label>
        <div class="form-selectgroup">
            @foreach(['Pegawai', 'Mahasiswa', 'Dosen'] as $role)
            <label class="form-selectgroup-item">
                <input type="checkbox" name="only_show_on[]" value="{{ $role }}" class="form-selectgroup-input" {{ in_array($role, $showOn) ? 'checked' : '' }}>
                <span class="form-selectgroup-label">{{ $role }}</span>
            </label>
            @endforeach
        </div>
    </div>

    <x-tabler.form-input type="file" name="file_template" label="Template Word (.docx)" accept=".docx">
        @if($modelExists && $layanan->file_template)
            <small class="text-success d-block mt-1">Template saat ini: {{ basename($layanan->file_template) }}</small>
        @endif
    </x-tabler.form-input>

    <div class="divider">Fitur Tambahan</div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <x-tabler.form-checkbox name="is_fitur_diskusi" value="1" label="Aktifkan Diskusi" :checked="$layanan->is_fitur_diskusi ?? false" switch />
        </div>
        <div class="col-md-6 mb-2">
            <x-tabler.form-checkbox name="is_fitur_disposisi" value="1" label="Aktifkan Disposisi" :checked="$layanan->is_fitur_disposisi ?? true" switch />
        </div>
        <div class="col-md-6 mb-2">
            <x-tabler.form-checkbox name="is_fitur_feedback" value="1" label="Aktifkan Feedback" :checked="$layanan->is_fitur_feedback ?? true" switch />
        </div>
    </div>

    <div class="mb-3 mt-3">
        <x-tabler.form-checkbox name="is_active" value="1" label="Layanan Aktif" :checked="$layanan->is_active ?? true" switch />
    </div>
</x-tabler.form-modal>
