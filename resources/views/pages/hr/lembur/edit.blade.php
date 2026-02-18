<form class="ajax-form" action="{{ route('hr.lembur.update', $lembur->hashid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <x-tabler.form-input name="judul" label="Judul Lembur" :value="$lembur->judul" required />
        </div>
        <div class="col-md-12">
            <x-tabler.form-textarea name="uraian_pekerjaan" label="Uraian Pekerjaan" rows="3" :value="$lembur->uraian_pekerjaan" />
        </div>
        <div class="col-md-12">
            <x-tabler.form-textarea name="alasan" label="Alasan Lembur" rows="2" :value="$lembur->alasan" />
        </div>
        <div class="col-md-4">
            <x-tabler.form-input type="date" name="tgl_pelaksanaan" label="Tanggal Pelaksanaan" :value="$lembur->tgl_pelaksanaan?->format('Y-m-d')" required />
        </div>
        <div class="col-md-4">
            <x-tabler.form-input type="time" name="jam_mulai" label="Jam Mulai" :value="$lembur->jam_mulai" required />
        </div>
        <div class="col-md-4">
            <x-tabler.form-input type="time" name="jam_selesai" label="Jam Selesai" :value="$lembur->jam_selesai" required />
        </div>
        <div class="col-md-12">
            <x-tabler.form-select name="pegawai_ids[]" label="Pegawai yang Lembur" multiple="true" required="true">
                @foreach($pegawais as $pegawai)
                    <option value="{{ $pegawai->pegawai_id }}" 
                        {{ $lembur->pegawais->contains($pegawai->pegawai_id) ? 'selected' : '' }}>
                        {{ $pegawai->latestDataDiri?->inisial }} - {{ $pegawai->latestDataDiri?->nama }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-4">
            <x-tabler.form-select name="is_dibayar" label="Dibayar?">
                <option value="1" {{ $lembur->is_dibayar ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ !$lembur->is_dibayar ? 'selected' : '' }}>Tidak</option>
            </x-tabler.form-select>
        </div>
        <div class="col-md-4">
            <x-tabler.form-select name="metode_bayar" label="Metode Bayar" :options="[
                'uang' => 'Uang',
                'cuti_pengganti' => 'Cuti Pengganti',
                'tidak_dibayar' => 'Tidak Dibayar'
            ]" :selected="$lembur->metode_bayar" />
        </div>
        <div class="col-md-4">
            <x-tabler.form-input type="number" name="nominal_per_jam" label="Nominal per Jam" step="1000" :value="$lembur->nominal_per_jam" />
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-secondary" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary" text="Update" />
    </div>
</form>
