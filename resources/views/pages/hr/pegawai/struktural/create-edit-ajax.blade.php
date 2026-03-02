<x-tabler.form-modal
    :title="isset($struktural) && $struktural->exists ? 'Edit Struktural' : 'Tambah Struktural'"
    :route="isset($struktural) && $struktural->exists ? route('hr.pegawai.struktural.update', [$pegawai->encrypted_pegawai_id, $struktural->encrypted_riwayatjabstruktural_id]) : route('hr.pegawai.struktural.store', $pegawai->encrypted_pegawai_id)"
    :method="isset($struktural) && $struktural->exists ? 'PUT' : 'POST'"
    :submitText="isset($struktural) && $struktural->exists ? 'Update' : 'Simpan'"
>
    @if(!isset($struktural) || !$struktural->exists)
        @if(isset($currentStruktural) && $currentStruktural)
        <div class="alert alert-info">
            Struktural saat ini: <strong>{{ $currentStruktural->orgUnit->name ?? '-' }}</strong>
            (sejak {{ $currentStruktural->tgl_awal?->format('d M Y') }})
        </div>
        @endif
    @endif

    <x-tabler.form-select type="select2" name="org_unit_id" label="Unit / Jabatan Struktural" required="true" data-dropdown-parent="#modalAction">
        <option value="">Pilih Unit / Jabatan Struktural</option>
        @foreach($units as $unit)
            <option value="{{ $unit->orgunit_id }}" {{ (isset($struktural) && $struktural->org_unit_id == $unit->orgunit_id) ? 'selected' : '' }}>
                {{ $unit->name }} ({{ ucfirst(str_replace('_', ' ', $unit->type)) }})
            </option>
        @endforeach
    </x-tabler.form-select>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_awal" label="Tanggal Mulai" value="{{ isset($struktural) && $struktural->tgl_awal ? $struktural->tgl_awal->format('Y-m-d') : old('tgl_awal', date('Y-m-d')) }}" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_akhir" label="Tanggal Akhir" value="{{ isset($struktural) && $struktural->tgl_akhir ? $struktural->tgl_akhir->format('Y-m-d') : old('tgl_akhir') }}" help="Kosongkan jika masih berlaku" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="no_sk" label="Nomor SK" value="{{ $struktural->no_sk ?? old('no_sk') }}" placeholder="SK/xxx/2026" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_pengesahan" label="Tanggal Pengesahan" value="{{ isset($struktural) && $struktural->tgl_pengesahan ? $struktural->tgl_pengesahan->format('Y-m-d') : old('tgl_pengesahan') }}" />
        </div>
    </div>

    <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="2" :value="$struktural->keterangan ?? old('keterangan')" />
</x-tabler.form-modal>
