<div class="card mb-3">
    <div class="card-header border-bottom">
        <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
            <h3 class="card-title mb-0">Riwayat Jabatan Fungsional</h3>
            <div class="ms-auto d-flex gap-2">
                <x-tabler.datatable-page-length dataTableId="jabatan-fungsional-table" />
                <x-tabler.datatable-search dataTableId="jabatan-fungsional-table" />
                <x-tabler.button 
                    style="primary" 
                    class="ajax-modal-btn" 
                    data-url="{{ route('hr.pegawai.jabatan-fungsional.create', $pegawai->encrypted_pegawai_id) }}" 
                    data-modal-title="Ubah Jabatan Fungsional"
                    icon="ti ti-edit"
                    text="Ubah Jafung" />
            </div>
        </div>
    </div>
    <x-tabler.datatable
        id="jabatan-fungsional-table"
        route="{{ route('hr.jabatan-fungsional-history.data', ['pegawai_id' => $pegawai->encrypted_pegawai_id]) }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'jabatan_nama', 'name' => 'jabatanFungsional.nama', 'title' => 'Jabatan'],
            ['data' => 'tmt', 'name' => 'tmt', 'title' => 'TMT', 'class' => 'text-center'],
            ['data' => 'no_sk_internal', 'name' => 'no_sk_internal', 'title' => 'SK Internal'],
            ['data' => 'no_sk_kopertis', 'name' => 'no_sk_kopertis', 'title' => 'SK Kopertis'],
            ['data' => 'approval_status', 'name' => 'approval_status', 'title' => 'Status', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ]"
    />
</div>
