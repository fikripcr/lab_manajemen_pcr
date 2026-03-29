<x-tabler.card-header class="border-bottom py-3">
    <div class="d-flex gap-2 align-items-center">
        <x-tabler.datatable-page-length :dataTableId="'pegawai-table'" />
        <x-tabler.datatable-filter :dataTableId="'pegawai-table'" type="button" :target="'#pegawai-filter-area'" />
        <x-tabler.datatable-search :dataTableId="'pegawai-table'" />
        <x-tabler.button class="btn-outline-success" icon="ti ti-file-spreadsheet" text="Export Excel" />
    </div>
</x-tabler.card-header>
<div class="collapse" id="pegawai-filter-area">
    <x-tabler.datatable-filter :dataTableId="'pegawai-table'" type="bare">
        <div class="row g-3">
            <div class="col-md-4">
                <x-tabler.form-select name="orgunit_id" label="Unit Kerja" placeholder="" class="mb-0" :options="$units">
                    <option value="all" selected>Semua Unit</option>
                </x-tabler.form-select>
            </div>
            <div class="col-md-4">
                <x-tabler.form-select name="posisi_id" label="Posisi" placeholder="" class="mb-0" :options="$posisi">
                    <option value="all" selected>Semua Posisi</option>
                </x-tabler.form-select>
            </div>
        </div>
    </x-tabler.datatable-filter>
</div>
<div class="table-responsive">
    <x-tabler.datatable
        id="pegawai-table"
        route="{{ route('hr.pegawai.data') }}"
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
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.generate-user');
    if (!btn) return;
    e.preventDefault();

    const url = btn.dataset.url;

    showConfirmation(
        'Konfirmasi Pembuatan User',
        'Apakah Anda yakin ingin membuat user untuk pegawai ini?\n\nDefault password: password123',
        'Ya, Buat User'
    ).then((result) => {
        if (result.isConfirmed) {
            showLoadingMessage('Memproses...', 'Sedang membuat akun pegawai');

            axios.post(url)
                .then(function(response) {
                    if (response.data.success) {
                        showSuccessMessage('Berhasil!', response.data.message);
                        $('#pegawai-table').DataTable().ajax.reload();
                    } else {
                        showErrorMessage('Gagal!', response.data.message);
                    }
                })
                .catch(function(error) {
                    const message = error.response?.data?.message || 'Terjadi kesalahan saat membuat user.';
                    showErrorMessage('Error!', message);
                });
        }
    });
});
</script>
@endpush

