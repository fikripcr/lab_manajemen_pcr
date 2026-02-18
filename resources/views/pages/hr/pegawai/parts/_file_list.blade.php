<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar File Pegawai</h3>
        <div class="card-actions">
            <x-tabler.button style="primary" icon="ti ti-upload" data-bs-toggle="modal" data-bs-target="#modal-upload-file" text="Unggah File" />
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-vcenter card-table" id="table-files" style="width:100%">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kategori</th>
                        <th>Nama File</th>
                        <th>Ukuran</th>
                        <th>Keterangan</th>
                        <th width="100" class="text-end">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
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
    <input type="hidden" name="pegawai_id" value="{{ $pegawai->hashid }}">
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
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
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
