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
                <x-tabler.form-input type="date" name="tgl_mulai" label="Tanggal Mulai" value="{{ $penugasan->tgl_mulai?->format('Y-m-d') }}" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input type="date" name="tgl_selesai" label="Tanggal Selesai" value="{{ $penugasan->tgl_selesai?->format('Y-m-d') }}" help="Kosongkan jika masih berlaku" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="no_sk" label="Nomor SK" value="{{ $penugasan->no_sk }}" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input type="date" name="tgl_sk" label="Tanggal SK" value="{{ $penugasan->tgl_sk?->format('Y-m-d') }}" />
            </div>
        </div>

        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="2" :value="$penugasan->keterangan" />
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary" text="Update" />
    </div>
</form>
