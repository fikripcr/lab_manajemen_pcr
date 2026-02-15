<form action="{{ route('hr.pegawai.inpassing.update', ['pegawai' => $pegawai->encrypted_pegawai_id, 'inpassing' => $inpassing->riwayatinpassing_id]) }}" method="POST" class="ajax-form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <x-tabler.form-select name="gol_inpassing_id" label="Golongan Inpassing" required="true">
                    <option value="">Pilih Golongan</option>
                    @foreach($golongan as $g)
                        <option value="{{ $g->gol_inpassing_id }}" {{ $inpassing->gol_inpassing_id == $g->gol_inpassing_id ? 'selected' : '' }}>{{ $g->golongan }} - {{ $g->nama_pangkat }}</option>
                    @endforeach
                </x-tabler.form-select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Terhitung Mulai Tanggal (TMT)</label>
                <input type="date" name="tmt" class="form-control" value="{{ $inpassing->tmt ? \Carbon\Carbon::parse($inpassing->tmt)->format('Y-m-d') : '' }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="no_sk" label="Nomor SK" value="{{ $inpassing->no_sk }}" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Tanggal SK</label>
                <input type="date" name="tgl_sk" class="form-control" value="{{ $inpassing->tgl_sk ? \Carbon\Carbon::parse($inpassing->tgl_sk)->format('Y-m-d') : '' }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Gaji Pokok</label>
                <input type="number" name="gaji_pokok" class="form-control" value="{{ $inpassing->gaji_pokok }}">
            </div>
             <div class="col-md-6 mb-3">
                <label class="form-label">Masa Kerja (Tahun)</label>
                <input type="number" name="masa_kerja_tahun" class="form-control" value="{{ $inpassing->masa_kerja_tahun }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Masa Kerja (Bulan)</label>
                <input type="number" name="masa_kerja_bulan" class="form-control" value="{{ $inpassing->masa_kerja_bulan }}">
            </div>
             <div class="col-md-6 mb-3">
                <label class="form-label">Angka Kredit</label>
                <input type="number" step="0.01" name="angka_kredit" class="form-control" value="{{ $inpassing->angka_kredit }}">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">File SK (PDF/Gambar max 2MB)</label>
                <input type="file" name="file_sk" class="form-control">
                @if($inpassing->file_sk)
                    <small class="text-muted">File saat ini: <a href="{{ Storage::url($inpassing->file_sk) }}" target="_blank">Lihat File</a></small>
                @endif
            </div>
            <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="3" :value="$inpassing->keterangan" />
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal">Batal</x-tabler.button>
        <x-tabler.button type="submit" class="btn-primary ms-auto">Simpan Perubahan</x-tabler.button>
    </div>
</form>
