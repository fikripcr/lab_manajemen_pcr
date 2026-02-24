<div class="card-body border-bottom py-3">
    <div class="d-flex flex-wrap gap-2">
        <div>
            <x-tabler.datatable-page-length :dataTableId="'pegawai-table'" />
        </div>
        <div class="ms-auto text-muted">
            <x-tabler.datatable-search :dataTableId="'pegawai-table'" />
        </div>
        <div>
            <x-tabler.button class="btn-outline-success w-100 w-sm-auto" icon="ti ti-file-spreadsheet" text="Export Excel" />
        </div>
    </div>
</div>
<div class="table-responsive">
    <x-tabler.datatable
        id="pegawai-table"
        route="{{ route('hr.pegawai.index') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'nama_lengkap', 'name' => 'nama', 'title' => 'Nama'],
            ['data' => 'status_kepegawaian', 'name' => 'status_kepegawaian', 'title' => 'Status'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'posisi', 'name' => 'posisi', 'title' => 'Posisi'],
            ['data' => 'unit', 'name' => 'unit', 'title' => 'Departemen'],
            // Prodi removed
            ['data' => 'penyelia', 'name' => 'penyelia', 'title' => 'Penyelia 1&2'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ]"
    />
</div>

@push('scripts')
<script>
$(document).on('click', '.generate-user', function(e) {
    e.preventDefault();

    const $button = $(this);
    const url = $button.data('url');

    if (!confirm('Apakah Anda yakin ingin membuat user untuk pegawai ini?\n\nDefault password: password123')) {
        return;
    }

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: response.message
                });

                // Reload datatable
                $('#pegawai-table').DataTable().ajax.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: response.message
                });
            }
        },
        error: function(xhr) {
            let message = 'Terjadi kesalahan saat membuat user.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message
            });
        }
    });
});
</script>
@endpush
