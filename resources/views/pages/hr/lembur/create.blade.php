<x-tabler.form-modal
    title="Tambah Lembur"
    route="{{ route('hr.lembur.store') }}"
    method="POST"
    submitText="Simpan"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="judul" label="Judul Lembur" required />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-textarea name="uraian_pekerjaan" label="Uraian Pekerjaan" rows="3" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-textarea name="alasan" label="Alasan Lembur" rows="2" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="date" name="tgl_pelaksanaan" label="Tanggal Pelaksanaan" required />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_mulai" label="Jam Mulai" required />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_selesai" label="Jam Selesai" required />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-select name="pegawai_ids[]" label="Pegawai yang Lembur" multiple="true" required="true">
                @foreach($pegawais as $pegawai)
                    <option value="{{ $pegawai->pegawai_id }}">
                        {{ $pegawai->latestDataDiri?->inisial }} - {{ $pegawai->latestDataDiri?->nama }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-select name="is_dibayar" label="Dibayar?">
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
            </x-tabler.form-select>
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-select name="metode_bayar" label="Metode Bayar" :options="[
                'uang' => 'Uang',
                'cuti_pengganti' => 'Cuti Pengganti',
                'tidak_dibayar' => 'Tidak Dibayar'
            ]" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="number" name="nominal_per_jam" label="Nominal per Jam" step="1000" />
        </div>
    </div>
</x-tabler.form-modal>
