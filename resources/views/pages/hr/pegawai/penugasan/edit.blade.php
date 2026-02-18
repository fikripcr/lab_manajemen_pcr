<x-tabler.form-modal
    title="Edit Penugasan"
    route="{{ route('hr.pegawai.penugasan.update', [$pegawai->pegawai_id, $penugasan->riwayatpenugasan_id]) }}"
    method="PUT"
    submitText="Update"
>
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
</x-tabler.form-modal>
