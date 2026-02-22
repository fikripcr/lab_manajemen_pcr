<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Daftar File Pegawai</h3>
        <div class="card-actions">
            <x-tabler.button 
                style="primary" 
                icon="ti ti-upload" 
                data-bs-toggle="modal" 
                data-bs-target="#modal-upload-file" 
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

<!-- Modal Upload -->
<x-tabler.form-modal
    id="modal-upload-file"
    id_form="form-upload-file"
    title="Unggah File Pegawai"
    route="#"
    method="POST"
    submitText="Unggah Sekarang"
    submitIcon="ti-upload"
    enctype="multipart/form-data"
>
    <input type="hidden" name="pegawai_id" value="{{ $pegawai->encrypted_pegawai_id }}">
    <div class="mb-3">
        <x-tabler.form-select name="jenisfile_id" label="Kategori File" required="true">
            <option value="">Pilih Kategori...</option>
            @foreach(\App\Models\Hr\JenisFile::where('is_active', 1)->get() as $jenis)
                <option value="{{ $jenis->jenisfile_id }}">{{ $jenis->jenisfile }}</option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input type="file" name="file" label="Pilih File" required="true" help="Maksimal 10MB (PDF, JPG, PNG, DOCX, dll)" />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="3" placeholder="Tambahkan catatan jika perlu..." />
    </div>
</x-tabler.form-modal>

@push('js')
<script>
    $(function() {
        $('#form-upload-file').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');
            
            submitBtn.prop('disabled', true).addClass('btn-loading');

            $.ajax({
                url: "{{ route('hr.pegawai.files.store', $pegawai->encrypted_pegawai_id) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        $('#modal-upload-file').modal('hide');
                        $('#form-upload-file')[0].reset();
                        location.reload(); // Reload to show new file
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                    toastr.error(message);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).removeClass('btn-loading');
                }
            });
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
                    $.ajax({
                        url: "{{ route('hr.pegawai.files.destroy', [$pegawai->encrypted_pegawai_id, ':id']) }}".replace(':id', encrypted_id),
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(res) {
                            if (res.success) {
                                location.reload(); // Reload to update list
                                toastr.success(res.message);
                            } else {
                                toastr.error(res.message);
                            }
                        }
                    });
                }
            });
        };
    });
</script>
@endpush
