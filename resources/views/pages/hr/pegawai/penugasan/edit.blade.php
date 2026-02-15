<div class="modal-header">
    <h5 class="modal-title">Edit Penugasan</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.pegawai.penugasan.update', [$pegawai->pegawai_id, $penugasan->riwayatpenugasan_id]) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <x-tabler.form-select class="select2-offline" name="org_unit_id" label="Unit / Jabatan" required="true" data-dropdown-parent="#modalAction">
            <option value="">Pilih Unit / Jabatan</option>
            @foreach($units as $unit)
                <option value="{{ $unit->org_unit_id }}" {{ $penugasan->org_unit_id == $unit->org_unit_id ? 'selected' : '' }}>
                    {{ $unit->name }} ({{ ucfirst(str_replace('_', ' ', $unit->type)) }})
                </option>
            @endforeach
        </x-tabler.form-select>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Tanggal Mulai</label>
                <input type="date" class="form-control" name="tgl_mulai" value="{{ $penugasan->tgl_mulai?->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" name="tgl_selesai" value="{{ $penugasan->tgl_selesai?->format('Y-m-d') }}">
                <small class="text-muted">Kosongkan jika masih berlaku</small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="no_sk" label="Nomor SK" value="{{ $penugasan->no_sk }}" />
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal SK</label>
                <input type="date" class="form-control" name="tgl_sk" value="{{ $penugasan->tgl_sk?->format('Y-m-d') }}">
            </div>
        </div>

        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="2" :value="$penugasan->keterangan" />
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal">Batal</x-tabler.button>
        <x-tabler.button type="submit" class="btn-primary">Update</x-tabler.button>
    </div>
</form>
