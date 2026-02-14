<form action="{{ route('eoffice.jenis-layanan.update', $layanan->jenislayanan_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-tabler.form-input name="nama_layanan" label="Nama Layanan" value="{{ $layanan->nama_layanan }}" required />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select name="kategori" label="Kategori" required>
                @foreach(['layanan', 'umum', 'akademik', 'keuangan', 'sdm', 'sarpras'] as $cat)
                    <option value="{{ $cat }}" {{ $layanan->kategori == $cat ? 'selected' : '' }}>{{ ucwords($cat) }}</option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="number" name="batas_pengerjaan" label="Batas Pengerjaan (Jam)" value="{{ $layanan->batas_pengerjaan }}" required />
        </div>
    </div>

    @php
        $showOn = is_array($layanan->only_show_on) ? $layanan->only_show_on : ($layanan->only_show_on ? json_decode($layanan->only_show_on, true) : []);
    @endphp

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
        @if($layanan->file_template)
            <small class="text-success d-block mt-1">Template saat ini: {{ basename($layanan->file_template) }}</small>
        @endif
    </x-tabler.form-input>

    <div class="row">
        <div class="col-md-6 mb-2">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_fitur_diskusi" value="1" {{ $layanan->is_fitur_diskusi ? 'checked' : '' }}>
                <span class="form-check-label">Aktifkan Diskusi</span>
            </label>
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_fitur_disposisi" value="1" {{ $layanan->is_fitur_disposisi ? 'checked' : '' }}>
                <span class="form-check-label">Aktifkan Disposisi</span>
            </label>
        </div>
        <div class="col-md-6 mb-2">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_fitur_feedback" value="1" {{ $layanan->is_fitur_feedback ? 'checked' : '' }}>
                <span class="form-check-label">Aktifkan Feedback</span>
            </label>
        </div>
    </div>

    <div class="mb-3 mt-3">
        <label class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $layanan->is_active ? 'checked' : '' }}>
            <span class="form-check-label">Layanan Aktif</span>
        </label>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
