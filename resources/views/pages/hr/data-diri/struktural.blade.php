@php $isPerPegawai = isset($pegawai); @endphp
<div class="card mb-3">
    <div class="card-header border-bottom">
        <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
            <h3 class="card-title mb-0">Riwayat Jabatan Struktural</h3>
            <div class="ms-auto d-flex gap-2">
                <x-tabler.datatable-page-length dataTableId="struktural-table" />
                <x-tabler.datatable-search dataTableId="struktural-table" />
                @if($isPerPegawai)
                    <x-tabler.button
                        style="secondary"
                        href="{{ route('hr.pegawai.mass-struktural.index') }}"
                        icon="ti ti-sitemap"
                        text="Struktural Massal" />
                    <x-tabler.button
                        style="primary"
                        class="ajax-modal-btn"
                        data-url="{{ route('hr.pegawai.struktural.create', $pegawai->encrypted_pegawai_id) }}"
                        data-modal-title="Tambah Struktural"
                        icon="ti ti-plus"
                        text="Tambah" />
                @endif
            </div>
        </div>
    </div>
    <x-tabler.datatable
        id="struktural-table"
        route="{{ $isPerPegawai ? route('hr.pegawai.struktural.data', ['pegawai' => $pegawai->encrypted_pegawai_id]) : route('hr.struktural.index') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'unit_struktural', 'name' => 'orgUnit.name', 'title' => 'Unit / Jabatan Struktural'],
            ['data' => 'tgl_awal', 'name' => 'tgl_awal', 'title' => 'Tgl Mulai', 'class' => 'text-center'],
            ['data' => 'tgl_akhir', 'name' => 'tgl_akhir', 'title' => 'Tgl Akhir', 'class' => 'text-center'],
            ['data' => 'no_sk', 'name' => 'no_sk', 'title' => 'No. SK'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end'],
        ]"
    />
</div>
