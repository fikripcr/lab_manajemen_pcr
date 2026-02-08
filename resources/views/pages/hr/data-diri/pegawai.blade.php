<div class="card-body border-bottom py-3">
    <div class="d-flex flex-wrap gap-2">
        <div>
            <x-tabler.datatable-page-length :dataTableId="'pegawai-table'" />
        </div>
        <div class="ms-auto text-muted">
            <x-tabler.datatable-search :dataTableId="'pegawai-table'" />
        </div>
        <div>
            <button class="btn btn-outline-success w-100 w-sm-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-spreadsheet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                    <path d="M8 11h8v7h-8z"></path>
                    <path d="M8 15h8"></path>
                    <path d="M11 11v7"></path>
                </svg>
                Export Excel
            </button>
        </div>
    </div>
</div>
<div class="table-responsive">
    <x-tabler.datatable 
        id="pegawai-table"
        route="{{ route('hr.pegawai.index') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
            ['data' => 'nama_lengkap', 'name' => 'nama', 'title' => 'Nama'],
            ['data' => 'status_kepegawaian', 'name' => 'status_kepegawaian', 'title' => 'Status'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'posisi', 'name' => 'posisi', 'title' => 'Posisi'],
            ['data' => 'unit', 'name' => 'unit', 'title' => 'Departemen'],
            ['data' => 'prodi', 'name' => 'prodi', 'title' => 'Prodi'],
            ['data' => 'penyelia', 'name' => 'penyelia', 'title' => 'Penyelia 1&2'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end'],
        ]"
    />
</div>
