<x-tabler.form-modal
    :title="isset($penugasan) && $penugasan->exists ? 'Edit Penugasan' : 'Tambah Penugasan'"
    :route="isset($penugasan) && $penugasan->exists ? route('hr.pegawai.penugasan.update', [$pegawai->pegawai_id, $penugasan->riwayatpenugasan_id]) : route('hr.pegawai.penugasan.store', $pegawai->pegawai_id)"
    :method="isset($penugasan) && $penugasan->exists ? 'PUT' : 'POST'"
    :submitText="isset($penugasan) && $penugasan->exists ? 'Update' : 'Simpan'"
>
    @if(!isset($penugasan) || !$penugasan->exists)
        @if($currentPenugasan)
        <div class="alert alert-info">
            Penugasan saat ini: <strong>{{ $currentPenugasan->orgUnit->name ?? '-' }}</strong>
            (sejak {{ $currentPenugasan->tgl_mulai?->format('d M Y') }})
        </div>
        @endif
    @endif

    <x-tabler.form-select type="select2" name="org_unit_id" label="Unit / Jabatan" required="true" data-dropdown-parent="#modalAction">
        <option value="">Pilih Unit / Jabatan</option>
        @foreach($units as $unit)
            <option value="{{ $unit->org_unit_id }}" {{ (isset($penugasan) && $penugasan->org_unit_id == $unit->org_unit_id) ? 'selected' : '' }}>
                {{ $unit->name }} ({{ ucfirst(str_replace('_', ' ', $unit->type)) }})
            </option>
        @endforeach
    </x-tabler.form-select>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_mulai" label="Tanggal Mulai" value="{{ isset($penugasan) && $penugasan->tgl_mulai ? $penugasan->tgl_mulai->format('Y-m-d') : old('tgl_mulai', date('Y-m-d')) }}" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_selesai" label="Tanggal Selesai" value="{{ isset($penugasan) && $penugasan->tgl_selesai ? $penugasan->tgl_selesai->format('Y-m-d') : old('tgl_selesai') }}" help="Kosongkan jika masih berlaku" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="no_sk" label="Nomor SK" value="{{ $penugasan->no_sk ?? old('no_sk') }}" placeholder="SK/xxx/2026" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_sk" label="Tanggal SK" value="{{ isset($penugasan) && $penugasan->tgl_sk ? $penugasan->tgl_sk->format('Y-m-d') : old('tgl_sk') }}" />
        </div>
    </div>

    <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="2" :value="$penugasan->keterangan ?? old('keterangan')" />
</x-tabler.form-modal>
