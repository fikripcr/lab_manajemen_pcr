<div class="modal-header">
    <h5 class="modal-title">Form Pengajuan Izin</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('hr.perizinan.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <x-tabler.form-select class="select2-pegawai" name="pengusul" label="Pegawai (Pengusul)" required="true" data-placeholder="Cari pegawai..." />
        <x-tabler.form-select name="jenisizin_id" id="jenisizin_id" label="Jenis Izin" required="true">
            <option value="">Pilih Jenis...</option>
            @foreach ($jenisIzin as $jenis)
                <option value="{{ $jenis->jenisizin_id }}" data-waktu="{{ $jenis->pemilihan_waktu }}">
                    {{ $jenis->nama }} {{ $jenis->max_hari ? "(Maks $jenis->max_hari hari)" : "" }}
                </option>
            @endforeach
        </x-tabler.form-select>
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
        <x-tabler.form-textarea name="pekerjaan_ditinggalkan" label="Pekerjaan yang ditinggalkan" rows="2" placeholder="Sebutkan tugas/pekerjaan yang didelegasikan..." />
        <x-tabler.form-textarea name="alamat_izin" label="Alamat selama izin" rows="2" placeholder="Alamat atau nomor telepon yang bisa dihubungi..." />
        <x-tabler.form-textarea name="keterangan" label="Keterangan Tambahan" rows="2" placeholder="Alasan detail pengajuan..." />
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
