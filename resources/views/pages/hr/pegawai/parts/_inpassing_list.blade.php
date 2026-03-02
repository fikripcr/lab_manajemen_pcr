<div class="card mb-3">
    <div class="card-header border-bottom">
        <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
            <h3 class="card-title mb-0">Riwayat Inpassing</h3>
            <div class="ms-auto d-flex gap-2">
                <x-tabler.datatable-page-length dataTableId="inpassing-table" />
                <x-tabler.datatable-search dataTableId="inpassing-table" />
                <x-tabler.button 
                    style="primary" 
                    class="ajax-modal-btn" 
                    data-url="{{ route('hr.pegawai.inpassing.create', $pegawai->encrypted_pegawai_id) }}" 
                    data-modal-title="Tambah Inpassing"
                    icon="ti ti-plus"
                    text="Ubah Inpassing" />
            </div>
        </div>
    </div>
    <x-tabler.datatable
        id="inpassing-table"
        route="{{ route('hr.inpassing.data', ['pegawai_id' => $pegawai->encrypted_pegawai_id]) }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'golongan_nama', 'name' => 'golonganInpassing.golongan', 'title' => 'Golongan'],
            ['data' => 'tmt', 'name' => 'tmt', 'title' => 'TMT', 'class' => 'text-center'],
            ['data' => 'no_sk', 'name' => 'no_sk', 'title' => 'No. SK'],
            ['data' => 'angka_kredit', 'name' => 'angka_kredit', 'title' => 'Kredit', 'class' => 'text-end'],
            ['data' => 'approval_status', 'name' => 'approval_status', 'title' => 'Status', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end'],
        ]"
    />
</div>
