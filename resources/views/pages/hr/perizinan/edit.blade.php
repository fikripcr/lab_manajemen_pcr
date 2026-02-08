<div class="modal-header">
    <h5 class="modal-title">Edit Pengajuan Izin</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('hr.perizinan.update', $perizinan->hashid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label required">Pegawai (Pengusul)</label>
            <select class="form-select select2-pegawai-edit" name="pengusul" required data-placeholder="Cari pegawai...">
                <option value="{{ $perizinan->pengusul }}" selected>
                    {{ $perizinan->pengusulPegawai?->latestDataDiri->inisial }} - {{ $perizinan->pengusulPegawai?->latestDataDiri->nama }}
                </option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label required">Jenis Izin</label>
            <select class="form-select" name="jenisizin_id" id="jenisizin_id_edit" required>
                @foreach ($jenisIzin as $jenis)
                    <option value="{{ $jenis->jenisizin_id }}" data-waktu="{{ $jenis->pemilihan_waktu }}" {{ $perizinan->jenisizin_id == $jenis->jenisizin_id ? 'selected' : '' }}>
                        {{ $jenis->nama }} {{ $jenis->max_hari ? "(Maks $jenis->max_hari hari)" : "" }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label required">Tanggal Awal</label>
                <input type="date" class="form-control" name="tgl_awal" value="{{ $perizinan->tgl_awal?->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label required">Tanggal Akhir</label>
                <input type="date" class="form-control" name="tgl_akhir" value="{{ $perizinan->tgl_akhir?->format('Y-m-d') }}" required>
            </div>
        </div>
        <div id="waktu-jam-edit" class="{{ in_array($perizinan->jenisIzin?->pemilihan_waktu, ['jam', 'tgl-jam']) ? '' : 'd-none' }}">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Jam Awal</label>
                    <input type="time" class="form-control" name="jam_awal" value="{{ $perizinan->jam_awal }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jam Akhir</label>
                    <input type="time" class="form-control" name="jam_akhir" value="{{ $perizinan->jam_akhir }}">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Pekerjaan yang ditinggalkan</label>
            <textarea class="form-control" name="pekerjaan_ditinggalkan" rows="2">{{ $perizinan->pekerjaan_ditinggalkan }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat selama izin</label>
            <textarea class="form-control" name="alamat_izin" rows="2">{{ $perizinan->alamat_izin }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan Tambahan</label>
            <textarea class="form-control" name="keterangan" rows="2">{{ $perizinan->keterangan }}</textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>

<script>
(async function() {
    await window.loadSelect2();
    $('.select2-pegawai-edit').select2({
        dropdownParent: $('#modalAction'),
        theme: 'bootstrap-5',
        ajax: {
            url: '{{ route('hr.pegawai.select2-search') }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return { results: data };
            },
            cache: true
        },
        minimumInputLength: 2
    });

    $('#jenisizin_id_edit').on('change', function() {
        const waktu = $(this).find(':selected').data('waktu');
        if (waktu === 'jam' || waktu === 'tgl-jam') {
            $('#waktu-jam-edit').removeClass('d-none');
        } else {
            $('#waktu-jam-edit').addClass('d-none').find('input').val('');
        }
    });
})();
</script>
