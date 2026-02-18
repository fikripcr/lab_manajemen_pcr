<x-tabler.form-modal
    title="Tambah Data Indisipliner"
    route="{{ route('hr.indisipliner.store') }}"
    method="POST"
>
    <div class="row mb-3">
        <div class="col-md-6">
            <x-tabler.form-select name="jenisindisipliner_id" label="Jenis Pelanggaran" required="true">
                <option value="">Pilih Jenis...</option>
                @foreach ($jenisIndisipliner as $jenis)
                    <option value="{{ $jenis->jenisindisipliner_id }}">{{ $jenis->jenis_indisipliner }}</option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tgl_indisipliner" label="Tanggal" required="true" />
        </div>
    </div>
    <div class="mb-3">
        <x-tabler.form-select class="select2-pegawai" name="pegawai_id[]" label="Pegawai" multiple="true" required="true" data-placeholder="Cari pegawai..." />
        <small class="text-muted">Pilih satu atau lebih pegawai</small>
    </div>
    <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="3" required="true" placeholder="Jelaskan detail pelanggaran..." />
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
})();
</script>
