<x-tabler.form-modal
    title="Edit Pengajuan Izin"
    route="{{ route('hr.perizinan.update', $perizinan->encrypted_perizinan_id) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <x-tabler.form-select class="select2-pegawai-edit" name="pengusul" label="Pegawai (Pengusul)" required="true" data-placeholder="Cari pegawai...">
        <option value="{{ $perizinan->pengusul }}" selected>
            {{ $perizinan->pengusulPegawai?->latestDataDiri->inisial }} - {{ $perizinan->pengusulPegawai?->latestDataDiri->nama }}
        </option>
    </x-tabler.form-select>
    <x-tabler.form-select name="jenisizin_id" id="jenisizin_id_edit" label="Jenis Izin" required="true">
        @foreach ($jenisIzin as $jenis)
            <option value="{{ $jenis->jenisizin_id }}" data-waktu="{{ $jenis->pemilihan_waktu }}" {{ $perizinan->jenisizin_id == $jenis->jenisizin_id ? 'selected' : '' }}>
                {{ $jenis->nama }} {{ $jenis->max_hari ? "(Maks $jenis->max_hari hari)" : "" }}
            </option>
        @endforeach
    </x-tabler.form-select>
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tgl_awal" label="Tanggal Awal" value="{{ $perizinan->tgl_awal?->format('Y-m-d') }}" required="true" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tgl_akhir" label="Tanggal Akhir" value="{{ $perizinan->tgl_akhir?->format('Y-m-d') }}" required="true" />
        </div>
    </div>
    <div id="waktu-jam-edit" class="{{ in_array($perizinan->jenisIzin?->pemilihan_waktu, ['jam', 'tgl-jam']) ? '' : 'd-none' }}">
        <div class="row">
            <div class="col-md-6">
                <x-tabler.form-input type="time" name="jam_awal" label="Jam Awal" value="{{ $perizinan->jam_awal }}" />
            </div>
            <div class="col-md-6">
                <x-tabler.form-input type="time" name="jam_akhir" label="Jam Akhir" value="{{ $perizinan->jam_akhir }}" />
            </div>
        </div>
    </div>
    <x-tabler.form-textarea name="pekerjaan_ditinggalkan" label="Pekerjaan yang ditinggalkan" rows="2" :value="$perizinan->pekerjaan_ditinggalkan" />
    <x-tabler.form-textarea name="alamat_izin" label="Alamat selama izin" rows="2" :value="$perizinan->alamat_izin" />
    <x-tabler.form-textarea name="keterangan" label="Keterangan Tambahan" rows="2" :value="$perizinan->keterangan" />
</x-tabler.form-modal>

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
