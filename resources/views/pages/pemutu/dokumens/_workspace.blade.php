@php
    $isRenopPoint = $type === 'poin' && strtolower(trim($item->dokumen->jenis ?? '')) === 'renop';
    $showIndikatorSection = $type === 'poin' && ($item->is_hasilkan_indikator || $isRenopPoint);

    $childrenColumns = [
        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ['data' => 'judul', 'name' => 'judul', 'title' => 'Judul / Nama'],
        ['data' => 'jumlah_turunan', 'name' => 'jumlah_turunan', 'title' => 'Jumlah Turunan'],
        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ];

    $indikatorColumns = [
        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Nama Indikator'],
        ['data' => 'unit_target', 'name' => 'unit_target', 'title' => 'Unit & Target', 'orderable' => false, 'searchable' => false],
        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ];

    $poinChildrenColumns = [
        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ['data' => 'judul', 'name' => 'judul', 'title' => 'Sub-Dokumen'],
        ['data' => 'jenis', 'name' => 'jenis', 'title' => 'Jenis'],
        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ];
@endphp

<div class="card">
    <div class="card-body">

        {{-- ═══════════════════════════════════════════════════
             ALWAYS-VISIBLE HEADER: Title, Badge, Action Buttons
             ═══════════════════════════════════════════════════ --}}
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                @if($type === 'dokumen')
                    <div class="badge bg-primary-lt mb-2">{{ strtoupper($item->jenis) }}</div>
                    <h2 class="m-0">{{ $item->judul }}</h2>
                    @if($item->kode)
                        <div class="text-muted mt-1">Kode: {{ $item->kode }}</div>
                    @endif
                @elseif($type === 'poin')
                    <div class="badge bg-purple-lt mb-2">POIN ({{ strtoupper($item->dokumen->jenis) }})</div>
                    <h2 class="m-0">{{ $item->judul }}</h2>
                    <div class="text-muted mt-1">Kode: {{ $item->kode }}</div>
                @endif
            </div>
            <div class="d-flex gap-2 flex-shrink-0">
                <div class="btn-group">
                    @if($type === 'dokumen')
                        <x-tabler.button type="success" class="btn-sm ajax-modal-btn" text="Approval" icon="ti ti-users"
                            data-url="{{ route('pemutu.dokumens.approve.create', $item->encrypted_dok_id) }}"
                            data-modal-title="Form Approval Dokumen" />


                        <x-tabler.button type="primary" class="btn-sm btn-secondary ajax-modal-btn me-0" text="" icon="ti ti-edit"
                            data-url="{{ route('pemutu.dokumen-spmi.edit', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}"
                            data-modal-title="Ubah Dokumen" />
                        <x-tabler.button type="delete" class="btn-sm ajax-delete" text="" icon="ti ti-trash"
                            data-url="{{ route('pemutu.dokumen-spmi.destroy', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}"
                            data-title="Hapus Dokumen ini?" />

                    @elseif($type === 'poin')
                        @if(!$isRenopPoint)
                            {{-- <x-tabler.button class="btn-primary btn-sm ajax-modal-btn"
                                data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'parent_doksub_id' => $item->encrypted_doksub_id, 'parent_id' => $item->dok_id]) }}"
                                data-modal-title="Tambah Dokumen Turunan" icon="ti ti-plus" text="Tambah Turunan Dokumen" /> --}}
                        @endif

                        @if($showIndikatorSection)
                            @if($isRenopPoint)
                                <x-tabler.button class="btn-success"
                                    href="{{ route('pemutu.indikators.create', ['parent_dok_id' => $item->encrypted_dok_id, 'parent_doksub_id' => $item->encrypted_doksub_id, 'type' => 'renop', 'is_renop_context' => 1, 'redirect_to' => url()->current()]) }}"
                                    icon="ti ti-plus" text="Tambah Indikator" />
                            @else
                                <x-tabler.button class="btn-success ajax-modal-btn"
                                    data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'indikator', 'parent_doksub_id' => $item->encrypted_doksub_id, 'is_renop_context' => 0]) }}"
                                    data-modal-title="Tambah Indikator" icon="ti ti-plus" text="Tambah Indikator" />
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════
             TAB NAVIGATION
             ═══════════════════════════════════════════════════ --}}
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#tab-subdokumen"
                   class="nav-link active"
                   data-bs-toggle="tab"
                   role="tab"
                   aria-selected="true">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                        <line x1="9" y1="13" x2="15" y2="13"/>
                        <line x1="9" y1="17" x2="15" y2="17"/>
                    </svg>
                    @if($type === 'dokumen') Sub-Dokumen @else Poin @endif
                </a>
            </li>
            @if($type === 'dokumen')
            <li class="nav-item" role="presentation">
                <a href="#tab-informasi"
                   class="nav-link"
                   data-bs-toggle="tab"
                   role="tab"
                   aria-selected="false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="12" cy="12" r="9"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                        <polyline points="11 12 12 12 12 16 13 16"/>
                    </svg>
                    Approval Dokumen
                </a>
            </li>
            @endif
        </ul>

        {{-- ═══════════════════════════════════════════════════
             TAB CONTENT
             ═══════════════════════════════════════════════════ --}}
        <div class="tab-content">

            {{-- ─────────────────────────────────────────────
                 TAB 1: Sub-Dokumen (Active by default)
                 ───────────────────────────────────────────── --}}
            <div class="tab-pane active show" id="tab-subdokumen" role="tabpanel">

                @if($type === 'dokumen')
                    <div class="card bg-transparent shadow-none border">
                        <div class="card-header border-0 d-flex justify-content-between align-items-center">
                            <h2 class="card-title">Daftar {{ $childLabel ?? 'Turunan' }}</h2>
                            <x-tabler.button class="ajax-modal-btn"
                                text="Tambah {{ $childLabel ?? 'Turunan' }}"
                                icon="ti ti-plus"
                                data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => $isDokSubBased ? 'poin' : 'dokumen', 'parent_id' => $item->encrypted_dok_id]) }}"
                                data-modal-title="Tambah {{ $childLabel ?? 'Turunan' }}"
                                size="sm" />
                        </div>
                        <x-tabler.datatable
                            id="children-table"
                            :url="route('pemutu.dokumen-spmi.children-data', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id])"
                            :columns="$childrenColumns"
                            ajax-load />
                    </div>

                @elseif($type === 'poin')

                    @if($showIndikatorSection)
                        <div class="card bg-transparent shadow-none border mb-3">
                            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                <h2 class="card-title">
                                    {{ $isRenopPoint ? 'Daftar Indikator Renop' : 'Indikator Terlampir' }}
                                    <span class="badge bg-muted-lt ms-2">{{ $item->indikators->count() }}</span>
                                </h2>
                                @if($isRenopPoint)
                                    <x-tabler.button class="btn-danger"
                                        href="{{ route('pemutu.indikators.create', ['parent_dok_id' => $item->encrypted_dok_id, 'parent_doksub_id' => $item->encrypted_doksub_id, 'type' => 'renop', 'is_renop_context' => 1, 'redirect_to' => url()->current()]) }}"
                                        icon="ti ti-plus" text="Tambah Indikator" size="sm" />
                                @else
                                    <x-tabler.button class="ajax-modal-btn"
                                        text="Tambah Indikator" icon="ti ti-plus"
                                        data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'indikator', 'parent_doksub_id' => $item->encrypted_doksub_id, 'is_renop_context' => 0]) }}"
                                        data-modal-title="Tambah Indikator" size="sm" />
                                @endif
                            </div>
                            <x-tabler.datatable
                                id="indikators-table"
                                :url="route('pemutu.dokumen-spmi.children-data', ['type' => 'poin_indikator', 'id' => $item->encrypted_doksub_id])"
                                :columns="$indikatorColumns"
                                ajax-load />
                        </div>
                    @endif

                    @if(!$isRenopPoint)
                        <div class="card bg-transparent shadow-none border">
                            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                <h2 class="card-title">Berdasarkan Poin Ini (Sub-Dokumen)</h2>
                                <x-tabler.button class="ajax-modal-btn"
                                    text="Tambah Dokumen Turunan" icon="ti ti-plus"
                                    data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'parent_doksub_id' => $item->encrypted_doksub_id, 'parent_id' => $item->dok_id]) }}"
                                    data-modal-title="Tambah Dokumen Turunan" size="sm" />
                            </div>
                            <x-tabler.datatable
                                id="poin-children-table"
                                :url="route('pemutu.dokumen-spmi.children-data', ['type' => 'poin_dokumen', 'id' => $item->encrypted_doksub_id])"
                                :columns="$poinChildrenColumns"
                                ajax-load />
                        </div>
                    @endif

                @endif
            </div>{{-- end #tab-subdokumen --}}

            {{-- ─────────────────────────────────────────────
                 TAB 2: Informasi & Riwayat Approval
                 ───────────────────────────────────────────── --}}
            <div class="tab-pane" id="tab-informasi" role="tabpanel">

                @if($item->isi)
                    <div class="mb-4">
                        <h4 class="mb-2 text-muted fw-medium">Konten Dokumen</h4>
                        <div class="markdown">
                            {!! $item->isi !!}
                        </div>
                    </div>
                    <hr class="my-4">
                @endif

                @if($type === 'dokumen')
                    <x-tabler.approval-history :approvals="$item->approvals" />
                @else
                    <x-tabler.empty-state
                        icon="ti ti-info-circle"
                        title="Tidak Ada Riwayat"
                        description="Riwayat approval hanya tersedia pada level Dokumen utama." />
                @endif

            </div>{{-- end #tab-informasi --}}

        </div>{{-- end .tab-content --}}

    </div>{{-- end .card-body --}}
</div>{{-- end .card --}}
