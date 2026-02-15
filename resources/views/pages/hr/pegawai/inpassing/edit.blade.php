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
                <x-tabler.form-input name="tmt" type="date" label="Terhitung Mulai Tanggal (TMT)" value="{{ $inpassing->tmt ? \Carbon\Carbon::parse($inpassing->tmt)->format('Y-m-d') : '' }}" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="no_sk" label="Nomor SK" value="{{ $inpassing->no_sk }}" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="tgl_sk" type="date" label="Tanggal SK" value="{{ $inpassing->tgl_sk ? \Carbon\Carbon::parse($inpassing->tgl_sk)->format('Y-m-d') : '' }}" required="true" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="gaji_pokok" type="number" label="Gaji Pokok" value="{{ $inpassing->gaji_pokok }}" />
            </div>
             <div class="col-md-6 mb-3">
                <x-tabler.form-input name="masa_kerja_tahun" type="number" label="Masa Kerja (Tahun)" value="{{ $inpassing->masa_kerja_tahun }}" />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-input name="masa_kerja_bulan" type="number" label="Masa Kerja (Bulan)" value="{{ $inpassing->masa_kerja_bulan }}" />
            </div>
             <div class="col-md-6 mb-3">
                <x-tabler.form-input name="angka_kredit" type="number" step="0.01" label="Angka Kredit" value="{{ $inpassing->angka_kredit }}" />
            </div>
            <div class="col-md-12 mb-3">
                <x-tabler.form-input type="file" name="file_sk" label="File SK (PDF/Gambar max 2MB)" />
                @if($inpassing->file_sk)
                    <small class="text-muted">File saat ini: <a href="{{ Storage::url($inpassing->file_sk) }}" target="_blank">Lihat File</a></small>
                @endif
            </div>
            <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="3" :value="$inpassing->keterangan" />
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary ms-auto" text="Simpan Perubahan" />
    </div>
</form>
