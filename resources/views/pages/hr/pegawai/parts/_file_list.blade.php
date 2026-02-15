<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar File Pegawai</h3>
        <div class="card-actions">
        <x-tabler.button class="btn-primary" icon="ti ti-upload" data-bs-toggle="modal" data-bs-target="#modal-upload-file">
            Unggah File
        </x-tabler.button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-vcenter card-table" id="table-files" style="width:100%">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kategori</th>
                        <th>Nama File</th>
                        <th>Ukuran</th>
                        <th>Keterangan</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Upload -->
<div class="modal modal-blur fade" id="modal-upload-file" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form-upload-file" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pegawai_id" value="{{ $pegawai->hashid }}">
                <div class="modal-header">
                    <h5 class="modal-title">Unggah File Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Kategori File</label>
                        <select name="jenisfile_id" class="form-select" required>
                            <option value="">Pilih Kategori...</option>
                            @foreach(\App\Models\Hr\JenisFile::where('is_active', 1)->get() as $jenis)
                                <option value="{{ $jenis->jenisfile_id }}">{{ $jenis->jenisfile }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Pilih File</label>
                        <input type="file" name="file" class="form-control" required>
                        <small class="text-muted">Maksimal 10MB (PDF, JPG, PNG, DOCX, dll)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-tabler.button type="button" class="btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</x-tabler.button>
                    <x-tabler.button type="submit" class="btn-primary">Unggah Sekarang</x-tabler.button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
    $(function() {
        const tableFiles = $('#table-files').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('hr.pegawai.files.data', $pegawai->hashid) }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'category', name: 'jenisfile.jenisfile' },
                { data: 'filename', name: 'media.file_name' },
                { data: 'size', name: 'media.size', searchable: false },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        $('#form-upload-file').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');
            
            submitBtn.prop('disabled', true).addClass('btn-loading');

            $.ajax({
                url: "{{ route('hr.pegawai.files.store', $pegawai->hashid) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        $('#modal-upload-file').modal('hide');
                        $('#form-upload-file')[0].reset();
                        tableFiles.ajax.reload();
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

        window.deleteFile = function(hashid) {
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
                        url: "{{ route('hr.pegawai.files.destroy', [$pegawai->hashid, ':id']) }}".replace(':id', hashid),
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(res) {
                            if (res.success) {
                                tableFiles.ajax.reload();
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
