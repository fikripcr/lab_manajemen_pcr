<div class="modal-header">
    <h5 class="modal-title">Tambah Penugasan</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.pegawai.penugasan.store', $pegawai->pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        @if($currentPenugasan)
        <div class="alert alert-info">
            Penugasan saat ini: <strong>{{ $currentPenugasan->orgUnit->name ?? '-' }}</strong>
            (sejak {{ $currentPenugasan->tgl_mulai?->format('d M Y') }})
        </div>
        @endif

        <div class="mb-3">
            <label class="form-label required">Unit / Jabatan</label>
            <select class="form-select select2-offline" name="org_unit_id" required data-dropdown-parent="#modalAction">
                <option value="">Pilih Unit / Jabatan</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->org_unit_id }}">
                        {{ $unit->name }} ({{ ucfirst(str_replace('_', ' ', $unit->type)) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Tanggal Mulai</label>
                <input type="date" class="form-control" name="tgl_mulai" value="{{ old('tgl_mulai', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" name="tgl_selesai" value="{{ old('tgl_selesai') }}">
                <small class="text-muted">Kosongkan jika masih berlaku</small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">No. SK</label>
                <input type="text" class="form-control" name="no_sk" value="{{ old('no_sk') }}" placeholder="SK/xxx/2026">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal SK</label>
                <input type="date" class="form-control" name="tgl_sk" value="{{ old('tgl_sk') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
