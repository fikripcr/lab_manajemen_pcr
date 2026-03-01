<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Daftar File Pegawai</h3>
        <div class="card-actions">
            <x-tabler.button 
                style="primary" 
                icon="ti ti-upload" 
                class="ajax-modal-btn"
                data-url="{{ route('hr.pegawai.files.create', $pegawai->hashid) }}"
                text="Unggah File" />
        </div>
    </div>
    <div class="card-body">
        @forelse($pegawai->files as $file)
        <div class="row row-cards">
            <div class="col-md-6 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-md me-3" style="background-color: var(--tblr-blue-lt);">
                                <i class="ti ti-file fs-2"></i>
                            </span>
                            <div class="flex-fill">
                                <h4 class="card-title mb-0">{{ $file->media->file_name ?? 'File' }}</h4>
                                <div class="text-muted small">{{ $file->jenisfile->jenisfile ?? 'Dokumen' }}</div>
                            </div>
                        </div>
                        
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Ukuran</div>
                                <div class="datagrid-content">{{ $file->media ? number_format($file->media->size / 1024, 2) . ' KB' : '-' }}</div>
                            </div>
                            @if($file->keterangan)
                            <div class="datagrid-item">
                                <div class="datagrid-title">Keterangan</div>
                                <div class="datagrid-content">{{ $file->keterangan }}</div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="mt-3">
                            <div class="btn-list">
                                @if($file->media)
                                <x-tabler.button 
                                    href="{{ $file->media->getUrl() }}" 
                                    style="ghost-info" 
                                    class="btn-sm" 
                                    icon="ti ti-download" 
                                    target="_blank" 
                                    text="Unduh" />
                                @endif
                                
                                <x-tabler.button 
                                    style="ghost-danger" 
                                    class="btn-sm" 
                                    icon="ti ti-trash"
                                    text="Hapus"
                                    onclick="deleteFile('{{ $file->encrypted_filepegawai_id }}')" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <x-tabler.empty-state 
            icon="ti ti-folder-off"
            title="Belum Ada File"
            description="Klik tombol di atas untuk mengunggah file pegawai."
        />
        @endforelse
    </div>
</div>


@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('ajax-form:success', function() {
            location.reload();
        });

        window.deleteFile = function(encrypted_id) {
            Swal.fire({
                title: 'Hapus File?',
                text: "Dokumen akan dihapus permanen dari riwayat!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = "{{ route('hr.pegawai.files.destroy', [$pegawai->encrypted_pegawai_id, ':id']) }}".replace(':id', encrypted_id);
                    axios.delete(url)
                        .then(function(res) {
                            if (res.data.success) {
                                toastr.success(res.data.message);
                                location.reload();
                            } else {
                                toastr.error(res.data.message);
                            }
                        })
                        .catch(function(error) {
                            toastr.error('Gagal menghapus file.');
                        });
                }
            });
        };
    });
</script>
@endpush
