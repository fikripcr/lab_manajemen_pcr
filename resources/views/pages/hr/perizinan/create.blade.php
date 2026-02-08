<div class="modal-header">
    <h5 class="modal-title">Form Pengajuan Izin</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('hr.perizinan.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label required">Pegawai (Pengusul)</label>
            <select class="form-select select2-pegawai" name="pengusul" required data-placeholder="Cari pegawai...">
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label required">Jenis Izin</label>
            <select class="form-select" name="jenisizin_id" id="jenisizin_id" required>
                <option value="">Pilih Jenis...</option>
                @foreach ($jenisIzin as $jenis)
                    <option value="{{ $jenis->jenisizin_id }}" data-waktu="{{ $jenis->pemilihan_waktu }}">
                        {{ $jenis->nama }} {{ $jenis->max_hari ? "(Maks $jenis->max_hari hari)" : "" }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label required">Tanggal Awal</label>
                <input type="date" class="form-control" name="tgl_awal" id="tgl_awal" required>
            </div>
            <div class="col-md-6">
                <label class="form-label required">Tanggal Akhir</label>
                <input type="date" class="form-control" name="tgl_akhir" id="tgl_akhir" required>
            </div>
        </div>
        <div id="waktu-jam" class="d-none">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Jam Awal</label>
                    <input type="time" class="form-control" name="jam_awal">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jam Akhir</label>
                    <input type="time" class="form-control" name="jam_akhir">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Pekerjaan yang ditinggalkan</label>
            <textarea class="form-control" name="pekerjaan_ditinggalkan" rows="2" placeholder="Sebutkan tugas/pekerjaan yang didelegasikan..."></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat selama izin</label>
            <textarea class="form-control" name="alamat_izin" rows="2" placeholder="Alamat atau nomor telepon yang bisa dihubungi..."></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan Tambahan</label>
            <textarea class="form-control" name="keterangan" rows="2" placeholder="Alasan detail pengajuan..."></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    </div>
</form>

<script>
(async function() {
    await window.loadSelect2();
    $('.select2-pegawai').select2({
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

    $('#jenisizin_id').on('change', function() {
        const waktu = $(this).find(':selected').data('waktu');
        if (waktu === 'jam' || waktu === 'tgl-jam') {
            $('#waktu-jam').removeClass('d-none');
        } else {
            $('#waktu-jam').addClass('d-none').find('input').val('');
        }
    });

    // Sync tgl_akhir with tgl_awal if tgl_akhir is empty
    $('#tgl_awal').on('change', function() {
        if (!$('#tgl_akhir').val()) {
            $('#tgl_akhir').val($(this).val());
        }
    });
})();
</script>
