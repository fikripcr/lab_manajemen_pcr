<div class="alert alert-info">
    <strong>{{ $pendaftaran->camaba->user->name }}</strong> - {{ $pendaftaran->no_pendaftaran }}
</div>

<div class="table-responsive">
    <table class="table table-vcenter table-bordered">
        <thead>
            <tr>
                <th>Dokumen</th>
                <th>File</th>
                <th class="text-center">Status</th>
                <th class="text-center">Diterima?</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendaftaran->dokumenUpload as $dokumen)
            <tr>
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
                <td class="text-center">
                    @if($dokumen->status_verifikasi === 'Valid')
                        <span class="badge bg-success text-white">Terverifikasi</span>
                    @elseif($dokumen->status_verifikasi === 'Ditolak')
                        <span class="badge bg-danger text-white">Ditolak</span>
                    @else
                        <span class="badge bg-warning text-white">Pending</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($dokumen->path_file)
                        <input type="checkbox" 
                               class="form-check-input toggle-verifikasi" 
                               data-dokumen-id="{{ $dokumen->dokumenupload_id }}"
                               {{ $dokumen->status_verifikasi === 'Valid' ? 'checked' : '' }}
                               title="Toggle untuk terima/tolak">
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada dokumen yang diupload</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="alert alert-info mt-3">
    <i class="ti ti-info-circle"></i>
    <strong>Cara Menggunakan:</strong> Toggle switch di kolom "Diterima?" untuk menandai berkas sebagai diterima (ON) atau ditolak (OFF). Perubahan akan tersimpan otomatis.
</div>
