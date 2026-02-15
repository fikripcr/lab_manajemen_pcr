<form action="{{ route('hr.pegawai.jabatan-fungsional.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="alert alert-info">
            Jabatan Fungsional saat ini: <strong>{{ $pegawai->latestJabatanFungsional->jabatanFungsional->jabfungsional ?? 'Belum ada' }}</strong><br>
            Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <x-tabler.form-select name="jabfungsional_id" label="Jabatan Fungsional Baru" required="true">
                    <option value="">Pilih Jabatan</option>
                    @foreach($jabatan as $item)
                        <option value="{{ $item->jabfungsional_id }}">{{ $item->jabfungsional }} ({{ $item->kode }})</option>
                    @endforeach
                </x-tabler.form-select>
            </div>

            <div class="col-md-6 mb-3">
                <x-tabler.form-input type="date" name="tmt" label="TMT (Terhitung Mulai Tanggal)" required="true" />
            </div>

            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="no_sk_internal" label="Nomor SK Internal" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary" text="Ajukan Perubahan" />
    </div>
</form>
