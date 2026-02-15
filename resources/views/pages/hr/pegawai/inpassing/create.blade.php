<form action="{{ route('hr.pegawai.inpassing.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <x-tabler.form-select name="gol_inpassing_id" label="Golongan Inpassing" required="true">
                    <option value="">Pilih Golongan</option>
                    @foreach($golongan as $g)
                        <option value="{{ $g->gol_inpassing_id }}">{{ $g->golongan }} - {{ $g->nama_pangkat }}</option>
                    @endforeach
                </x-tabler.form-select>
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="tmt" type="date" label="Terhitung Mulai Tanggal (TMT)" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="no_sk" label="Nomor SK" placeholder="Nomor SK" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="tgl_sk" type="date" label="Tanggal SK" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="gaji_pokok" type="number" label="Gaji Pokok" placeholder="Contoh: 3000000" />
            </div>
             <div class="col-md-6 mb-3">
                <x-tabler.form-input name="masa_kerja_tahun" type="number" label="Masa Kerja (Tahun)" placeholder="Tahun" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="masa_kerja_bulan" type="number" label="Masa Kerja (Bulan)" placeholder="Bulan" />
            </div>
             <div class="col-md-6 mb-3">
                <x-tabler.form-input name="angka_kredit" type="number" step="0.01" label="Angka Kredit" placeholder="0.00" />
            </div>
            <div class="col-md-12 mb-3">
                <x-tabler.form-input type="file" name="file_sk" label="File SK (PDF/Gambar max 2MB)" />
            </div>
            <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="3" placeholder="Keterangan tambahan..." />
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary ms-auto" text="Simpan" />
    </div>
</form>
