<form action="{{ route('eoffice.jenis-layanan.update', $layanan->jenislayanan_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label required">Nama Layanan</label>
        <input type="text" name="nama_layanan" class="form-control" value="{{ $layanan->nama_layanan }}" required>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label required">Kategori</label>
            <select name="kategori" class="form-select" required>
                @foreach(['layanan', 'umum', 'akademik', 'keuangan', 'sdm', 'sarpras'] as $cat)
                    <option value="{{ $cat }}" {{ $layanan->kategori == $cat ? 'selected' : '' }}>{{ ucwords($cat) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label required">Batas Pengerjaan (Jam)</label>
            <input type="number" name="batas_pengerjaan" class="form-control" value="{{ $layanan->batas_pengerjaan }}" required>
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

    <div class="mb-3">
        <label class="form-label">Template Word (.docx)</label>
        <input type="file" name="file_template" class="form-control" accept=".docx">
        @if($layanan->file_template)
            <small class="text-success">Template saat ini: {{ basename($layanan->file_template) }}</small>
        @endif
    </div>

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
