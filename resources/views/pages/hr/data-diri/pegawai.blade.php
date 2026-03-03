<div class="card-header border-bottom py-3">
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <div>
            <x-tabler.datatable-page-length :dataTableId="'pegawai-table'" />
        </div>
        <div>
            <x-tabler.datatable-search :dataTableId="'pegawai-table'" />
        </div>
        <div>
            <x-tabler.datatable-filter :dataTableId="'pegawai-table'">
                <div class="col-12">
                    <x-tabler.form-select id="filter-posisi" name="posisi_id" label="Filter Posisi" placeholder="Semua Posisi" class="mb-0">
                        <option value="">Semua Posisi</option>
                        @foreach($posisi as $p)
                            <option value="{{ $p->orgunit_id }}">{{ $p->name }}</option>
                        @endforeach
                    </x-tabler.form-select>
                </div>
                <div class="col-12 mt-2">
                    <x-tabler.form-select id="filter-unit" name="orgunit_id" label="Filter Unit" placeholder="Semua Unit" class="mb-0">
                        <option value="">Semua Unit</option>
                        @foreach($units as $u)
                            <option value="{{ $u->orgunit_id }}">{{ $u->name }}</option>
                        @endforeach
                    </x-tabler.form-select>
                </div>
            </x-tabler.datatable-filter>
        </div>
        <div class="ms-auto d-flex gap-2">
            <x-tabler.button class="btn-outline-success w-100 w-sm-auto" icon="ti ti-file-spreadsheet" text="Export Excel" />
        </div>
    </div>
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

    if (!confirm('Apakah Anda yakin ingin membuat user untuk pegawai ini?\n\nDefault password: password123')) {
        return;
    }

    axios.post(url)
        .then(function(response) {
            if (response.data.success) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', html: response.data.message });
                $('#pegawai-table').DataTable().ajax.reload();
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal!', html: response.data.message });
            }
        })
        .catch(function(error) {
            const message = error.response?.data?.message || 'Terjadi kesalahan saat membuat user.';
            Swal.fire({ icon: 'error', title: 'Error!', text: message });
        });
});
</script>
@endpush

