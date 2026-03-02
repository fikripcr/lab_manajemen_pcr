<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Riwayat Approval</h3>
    </div>
    <x-tabler.datatable
        id="approval-table"
        route="{{ route('hr.approval.index', ['pegawai_id' => $pegawai->encrypted_pegawai_id]) }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'tipe_request', 'name' => 'model', 'title' => 'Tipe'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end'],
        ]"
    />
</div>
