<x-tabler.card class="mb-3">
    <x-tabler.card-header title="Pengembangan Diri">
        <x-slot:actions>
            <div class="d-flex gap-2">
                <x-tabler.datatable-page-length dataTableId="pengembangan-table" />
                <x-tabler.datatable-search dataTableId="pengembangan-table" />
                <x-tabler.button 
                    type="create"
                    class="ajax-modal-btn" 
                    data-url="{{ route('hr.pegawai.pengembangan.create', $pegawai->encrypted_pegawai_id) }}" 
                    data-modal-title="Tambah Pengembangan Diri"
                    text="Tambah" />
            </div>
        </x-slot:actions>
    </x-tabler.card-header>
    <x-tabler.datatable
        id="pengembangan-table"
        route="{{ route('hr.pengembangan.data', ['pegawai_id' => $pegawai->encrypted_pegawai_id]) }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'nama_kegiatan', 'name' => 'nama_kegiatan', 'title' => 'Kegiatan'],
            ['data' => 'jenis_pengembangan', 'name' => 'jenis_pengembangan', 'title' => 'Jenis'],
            ['data' => 'penyelenggara', 'name' => 'penyelenggara', 'title' => 'Penyelenggara'],
            ['data' => 'tahun', 'name' => 'tahun', 'title' => 'Tahun', 'class' => 'text-center'],
            ['data' => 'sertifikat', 'name' => 'sertifikat', 'title' => 'Sertifikat', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end'],
        ]"
    />
</x-tabler.card>
