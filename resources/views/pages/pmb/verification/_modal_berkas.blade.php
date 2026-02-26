<div class="alert alert-info">
    <strong>{{ $pendaftaran->camaba->user->name }}</strong> - {{ $pendaftaran->no_pendaftaran }}
</div>

<form id="formVerifikasiBerkas" action="{{ route('pmb.verification.verify-batch') }}" method="POST">
    @csrf
    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->pendaftaran_id }}">
    
    <div class="table-responsive">
        <table class="table table-vcenter table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="50">
                        <input type="checkbox" id="checkAll" class="form-check-input">
                    </th>
                    <th>Dokumen</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftaran->dokumenUpload as $dokumen)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" name="dokumen_ids[]" value="{{ $dokumen->id }}" class="form-check-input doc-checkbox" data-status="{{ $dokumen->status_verifikasi }}">
                    </td>
                    <td>{{ $dokumen->jenisDokumen->nama_dokumen ?? '-' }}</td>
                    <td>
                        @if($dokumen->path_file)
                            <a href="{{ asset('storage/' . $dokumen->path_file) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="ti ti-download"></i> Download
                            </a>
                        @else
                            <span class="text-muted">Belum upload</span>
                        @endif
                    </td>
                    <td>
                        @if($dokumen->status_verifikasi === 'Valid')
                            <span class="badge bg-success text-white">Terverifikasi</span>
                        @elseif($dokumen->status_verifikasi === 'Ditolak')
                            <span class="badge bg-danger text-white">Ditolak</span>
                        @else
                            <span class="badge bg-warning text-white">Pending</span>
                        @endif
                    </td>
                    <td>{{ $dokumen->catatan_verifikasi ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada dokumen yang diupload</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pendaftaran->dokumenUpload->where('status_verifikasi', 'Pending')->count() > 0)
    <div class="mt-3">
        <div class="row">
            <div class="col-6">
                <x-tabler.form-select name="status" id="statusVerifikasi" label="Status" :options="['Valid' => 'Valid', 'Ditolak' => 'Ditolak']" :required="true" />
            </div>
            <div class="col-6">
                <x-tabler.form-input name="catatan" label="Catatan" placeholder="Catatan verifikasi (opsional)" />
            </div>
        </div>
        <div class="mt-3 text-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary btn-submit-verifikasi">
                <i class="ti ti-check"></i> Simpan Verifikasi
            </button>
        </div>
    </div>
    @endif
</form>

<script>
// Check all functionality
$('#checkAll').on('change', function() {
    const isChecked = $(this).is(':checked');
    $('.doc-checkbox').each(function() {
        const currentStatus = $(this).data('status');
        // Only allow checking pending documents
        if (currentStatus === 'Pending') {
            $(this).prop('checked', isChecked);
        }
    });
});

// Auto-check pending documents on load
$('.doc-checkbox').each(function() {
    const currentStatus = $(this).data('status');
    if (currentStatus === 'Pending') {
        $(this).prop('checked', true);
    } else {
        $(this).prop('disabled', true);
    }
});
</script>
