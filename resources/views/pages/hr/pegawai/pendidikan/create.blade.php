<form action="{{ route('hr.pegawai.pendidikan.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Data pendidikan yang ditambahkan akan berstatus <strong>Menunggu Persetujuan</strong>.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <x-tabler.form-select name="jenjang_pendidikan" label="Jenjang Pendidikan" required="true">
                    <option value="">Pilih Jenjang</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </x-tabler.form-select>
            </div>

            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="nama_pt" label="Nama Perguruan Tinggi" required="true" />
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Bidang Ilmu / Jurusan</label>
                <x-tabler.form-input name="bidang_ilmu" label="Bidang Ilmu" />
            </div>

            <div class="col-md-6 mb-3">
                <x-tabler.form-input type="date" name="tgl_ijazah" label="Tanggal Ijazah (Lulus)" required="true" />
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Kota Asal PT</label>
                <x-tabler.form-input name="kotaasal_pt" label="Kota PT" />
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Negara Asal PT</label>
                <x-tabler.form-input name="kodenegara_pt" label="Negara PT" placeholder="Indonesia" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy" text="Simpan" />
    </div>
</form>
