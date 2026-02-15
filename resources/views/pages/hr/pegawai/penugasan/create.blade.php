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

        <x-tabler.form-select class="select2-offline" name="org_unit_id" label="Unit / Jabatan" required="true" data-dropdown-parent="#modalAction">
            <option value="">Pilih Unit / Jabatan</option>
            @foreach($units as $unit)
                <option value="{{ $unit->org_unit_id }}">
                    {{ $unit->name }} ({{ ucfirst(str_replace('_', ' ', $unit->type)) }})
                </option>
            @endforeach
        </x-tabler.form-select>

        <div class="row">
            <div class="col-md-6 mb-3">
                <x-tabler.form-input type="date" name="tgl_mulai" label="Tanggal Mulai" value="{{ old('tgl_mulai', date('Y-m-d')) }}" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input type="date" name="tgl_selesai" label="Tanggal Selesai" value="{{ old('tgl_selesai') }}" help="Kosongkan jika masih berlaku" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="no_sk" label="Nomor SK" value="{{ old('no_sk') }}" placeholder="SK/xxx/2026" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input type="date" name="tgl_sk" label="Tanggal SK" value="{{ old('tgl_sk') }}" />
            </div>
        </div>

        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="2" />
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary" text="Simpan" />
    </div>
</form>
