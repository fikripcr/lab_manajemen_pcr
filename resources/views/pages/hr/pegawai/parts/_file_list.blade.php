<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar File Pegawai</h3>
        <div class="card-actions">
            <x-tabler.button style="primary" icon="ti ti-upload" data-bs-toggle="modal" data-bs-target="#modal-upload-file" text="Unggah File" />
        </div>
    </div>
    <div class="card-body p-0">
    <div class="card-table">
        <x-tabler.datatable
            id="table-files"
            route="{{ route('hr.pegawai.files.data', $pegawai->encrypted_pegawai_id) }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '50', 'orderable' => false, 'searchable' => false],
                ['data' => 'category', 'name' => 'jenisfile.jenisfile', 'title' => 'Kategori'],
                ['data' => 'filename', 'name' => 'media.file_name', 'title' => 'Nama File'],
                ['data' => 'size', 'name' => 'media.size', 'title' => 'Ukuran', 'searchable' => false],
                ['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '100', 'orderable' => false, 'searchable' => false, 'className' => 'text-end']
            ]"
        />
    </div>    </div>
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
                        $('#table-files').DataTable().ajax.reload();
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
                                $('#table-files').DataTable().ajax.reload();
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
