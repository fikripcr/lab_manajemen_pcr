<div class="modal-header">
    <h5 class="modal-title">Edit Data Indisipliner</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('hr.indisipliner.update', $indisipliner->hashid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label required">Jenis Pelanggaran</label>
                <select class="form-select" name="jenisindisipliner_id" required>
                    <option value="">Pilih Jenis...</option>
                    @foreach ($jenisIndisipliner as $jenis)
                        <option value="{{ $jenis->jenisindisipliner_id }}" {{ $indisipliner->jenisindisipliner_id == $jenis->jenisindisipliner_id ? 'selected' : '' }}>
                            {{ $jenis->jenis_indisipliner }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label required">Tanggal</label>
                <input type="date" class="form-control" name="tgl_indisipliner" value="{{ $indisipliner->tgl_indisipliner?->format('Y-m-d') }}" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label required">Pegawai</label>
            <select class="form-select select2-pegawai-edit" name="pegawai_id[]" multiple required data-placeholder="Cari pegawai...">
                @foreach ($indisipliner->indisiplinerPegawai as $ip)
                    <option value="{{ $ip->pegawai->pegawai_id }}" selected>
                        {{ $ip->pegawai->latestDataDiri->inisial ?? '' }} - {{ $ip->pegawai->latestDataDiri->nama ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Pilih satu atau lebih pegawai</small>
        </div>
        <div class="mb-3">
            <label class="form-label required">Keterangan</label>
            <textarea class="form-control" name="keterangan" rows="3" required>{{ $indisipliner->keterangan }}</textarea>
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
})();
</script>
