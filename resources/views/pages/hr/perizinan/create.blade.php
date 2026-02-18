<x-tabler.form-modal
    title="Form Pengajuan Izin"
    route="{{ route('hr.perizinan.store') }}"
    method="POST"
    submitText="Kirim Pengajuan"
>
    <x-tabler.form-select class="select2-pegawai" name="pengusul" label="Pegawai (Pengusul)" required="true" data-placeholder="Cari pegawai..." />
    <x-tabler.form-select name="jenisizin_id" id="jenisizin_id" label="Jenis Izin" required="true">
        <option value="">Pilih Jenis...</option>
        @foreach ($jenisIzin as $jenis)
            <option value="{{ $jenis->jenisizin_id }}" data-waktu="{{ $jenis->pemilihan_waktu }}">
                {{ $jenis->nama }} {{ $jenis->max_hari ? "(Maks $jenis->max_hari hari)" : "" }}
            </option>
        @endforeach
    </x-tabler.form-select>
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tgl_awal" id="tgl_awal" label="Tanggal Awal" required="true" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tgl_akhir" id="tgl_akhir" label="Tanggal Akhir" required="true" />
        </div>
    </div>
    <div id="waktu-jam" class="d-none">
        <div class="row">
            <div class="col-md-6">
                <x-tabler.form-input type="time" name="jam_awal" label="Jam Awal" />
            </div>
            <div class="col-md-6">
                <x-tabler.form-input type="time" name="jam_akhir" label="Jam Akhir" />
            </div>
        </div>
    </div>
    <x-tabler.form-textarea name="pekerjaan_ditinggalkan" label="Pekerjaan yang ditinggalkan" rows="2" placeholder="Sebutkan tugas/pekerjaan yang didelegasikan..." />
    <x-tabler.form-textarea name="alamat_izin" label="Alamat selama izin" rows="2" placeholder="Alamat atau nomor telepon yang bisa dihubungi..." />
    <x-tabler.form-textarea name="keterangan" label="Keterangan Tambahan" rows="2" placeholder="Alasan detail pengajuan..." />
</x-tabler.form-modal>

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
