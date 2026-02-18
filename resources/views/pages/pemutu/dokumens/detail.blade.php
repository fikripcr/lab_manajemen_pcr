@if(request()->ajax() || request()->has('ajax'))
    @php
        $parentJenis = $dokumen->jenis ? strtolower(trim($dokumen->jenis)) : '';
    @endphp
    <div class="modal-header">
        <h5 class="modal-title">Detail Dokumen: {{ $dokumen->judul }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
            @if($dokumen->jenis)
                <span class="badge badge-outline text-blue bg-blue-lt">{{ strtoupper($dokumen->jenis) }}</span>
            @endif
            @if($dokumen->kode)
                <span class="badge badge-outline text-muted" title="Kode Dokumen">{{ $dokumen->kode }}</span>
            @endif
            @if($dokumen->periode)
                <span class="badge badge-outline text-muted" title="Periode">{{ $dokumen->periode }}</span>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label text-muted small uppercase">Isi Dokumen</label>
            @if($dokumen->isi)
                <div class="markdown p-3 border rounded bg-light-lt" style="max-height: 300px; overflow-y: auto;">
                    {!! $dokumen->isi !!}
                </div>
            @else
                <div class="text-muted text-center py-4 fst-italic border rounded bg-light-lt">
                    Belum ada konten isi untuk dokumen ini.
                </div>
            @endif
        </div>

        <div class="datagrid">
            <div class="datagrid-item">
                <div class="datagrid-title">Created At</div>
                <div class="datagrid-content">{{ $dokumen->created_at->format('d M Y') }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Last Updated</div>
                <div class="datagrid-content">{{ $dokumen->updated_at->format('d M Y') }}</div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tutup</button>
        <x-tabler.button type="a" :href="route('pemutu.dokumens.show', $dokumen->dok_id)" class="btn-primary" text="Detail Lengkap" />
    </div>
@else
    @extends('layouts.tabler.app')

    @section('header')
    <x-tabler.page-header title="{{ $dokumen->judul }}" pretitle="Dokumen SPMI">
        <x-slot:actions>
            @if($dokumen->jenis === 'renop')
                <x-tabler.button type="button" class="btn-primary d-none d-sm-inline-block" href="{{ route('pemutu.dokumens.show-renop-with-indicators', $dokumen) }}" icon="ti ti-chart-bar" text="Akumulasi Indikator" />
            @endif

            <div class="btn-group shadow-sm" role="group">
                <x-tabler.button href="#" class="btn-white ajax-modal-btn" data-url="{{ route('pemutu.dokumens.edit', $dokumen) }}" data-modal-title="Edit Dokumen" icon="ti ti-pencil" text="Edit" />
                @if($isDokSubBased)
                    <x-tabler.button href="#" class="btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dok-subs.create', ['dok_id' => $dokumen->hashid]) }}" data-modal-title="Tambah {{ $childLabel }}" icon="ti ti-plus" text="{{ $childLabel }}" />
                @else
                    <x-tabler.button href="#" class="btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dokumens.create', ['parent_id' => $dokumen->hashid]) }}" data-modal-title="Tambah {{ $childLabel }}" icon="ti ti-plus" text="{{ $childLabel }}" />
                @endif
                <x-tabler.button href="#" class="btn-outline-danger ajax-delete" data-url="{{ route('pemutu.dokumens.destroy', $dokumen) }}" data-title="Hapus Dokumen?" data-text="Dokumen ini beserta sub-dokumennya akan dihapus permanen." icon="ti ti-trash" text="Hapus" />
            </div>
        </x-slot:actions>
    </x-tabler.page-header>
    @endsection

    @section('content')
    @php
        $childLabel = 'Sub Dokumen';
        $parentJenis = $dokumen->jenis ? strtolower(trim($dokumen->jenis)) : '';
        
        if($parentJenis) {
            $childLabel = match($parentJenis) {
                'visi' => 'Misi',
                'misi' => 'RPJP',
                'rjp' => 'Renstra',
                'renstra' => 'Renop',
                'renop' => 'Kegiatan / Poin',
                default => 'Sub Dokumen'
            };
        }

        $isDokSubBased = in_array($parentJenis, ['standar', 'formulir', 'manual_prosedur', 'renop']);
        $activeSubTab = request()->get('subtab', 'overview');
    @endphp

    <div class="page-body">
        <div class="container-xl">

            <ol class="breadcrumb" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="{{ route('pemutu.dokumens.index', ['tabs' => (in_array($parentJenis, ['standar', 'formulir', 'manual_prosedur']) ? 'standar' : 'kebijakan')]) }}">Dokumen SPMI</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>

            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" id="doc-detail-tabs">
                        <li class="nav-item">
                            <a href="#tab-overview" class="nav-link {{ $activeSubTab === 'overview' ? 'active' : '' }}" data-bs-toggle="tab" data-tab-id="overview">
                                <i class="ti ti-info-circle me-1"></i> Isi Dokumen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tab-children" class="nav-link {{ $activeSubTab === 'children' ? 'active' : '' }}" data-bs-toggle="tab" data-tab-id="children">
                                <i class="ti ti-hierarchy-2 me-1"></i> Daftar {{ $childLabel }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tab-approval" class="nav-link {{ $activeSubTab === 'approval' ? 'active' : '' }}" data-bs-toggle="tab" data-tab-id="approval">
                                <i class="ti ti-checkup-list me-1"></i> Approval & Legalitas
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <!-- Tab Isi -->
                    <div class="tab-pane {{ $activeSubTab === 'overview' ? 'active show' : '' }}" id="tab-overview">
                        <div class="card-body">
                            @if($dokumen->isi)
                                <div class="markdown p-4 border rounded shadow-sm mb-3" style="min-height: 150px;">
                                    {!! $dokumen->isi !!}
                                </div>
                            @else
                                <div class="text-muted text-center py-5 fst-italic border rounded bg-light-lt">
                                    Belum ada konten isi untuk dokumen ini.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab Children -->
                    <div class="tab-pane {{ $activeSubTab === 'children' ? 'active show' : '' }}" id="tab-children">
                        <div class="card-body p-0">
                            <x-tabler.datatable
                                id="table-sub-dokumen"
                                route="{{ route('pemutu.dokumens.children-data', $dokumen) }}"
                                ajax-load="true"
                                :columns="[
                                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false,'class'=>'text-center', 'width' => '5%'],
                                    ['data' => 'judul', 'name' => 'judul', 'title' => 'Judul '.$childLabel],
                                    ['data' => 'jumlah_turunan', 'name' => 'jumlah_turunan', 'title' => 'Jumlah Turunan', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '15%'],
                                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '15%']
                                ]"
                            />
                        </div>
                    </div>

                    <!-- Tab Approval -->
                    <div class="tab-pane {{ $activeSubTab === 'approval' ? 'active show' : '' }}" id="tab-approval">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">Riwayat Approval & Legalitas</h4>
                                <x-tabler.button 
                                    type="button" 
                                    class="btn-primary ajax-modal-btn" 
                                    data-url="{{ route('pemutu.dokumens.approve.create', $dokumen) }}" 
                                    data-modal-title="Submit Approval & Legalitas" 
                                    icon="ti ti-checkup-list" 
                                    text="Submit Approval" 
                                />
                            </div>

                            @if($dokumen->approvals->count() > 0)
                                <div class="divide-y">
                                    @foreach($dokumen->approvals()->latest()->get() as $approval)
                                        <div class="py-3">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="avatar avatar-sm rounded">{{ substr($approval->approver->nama ?? '?', 0, 1) }}</span>
                                                </div>
                                                <div class="col">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="font-weight-bold">{{ $approval->approver->nama ?? 'Unknown' }}</div>
                                                        <div class="text-muted small">{{ $approval->created_at->diffForHumans() }}</div>
                                                    </div>
                                                    <div class="text-muted small">{{ $approval->proses }} @if($approval->jabatan) ({{ $approval->jabatan }}) @endif</div>
                                                    
                                                    @foreach($approval->statuses as $status)
                                                        <div class="mt-2 p-3 rounded bg-body-tertiary border position-relative">
                                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                                <span class="badge bg-{{ $status->status_approval === 'terima' ? 'success' : ($status->status_approval === 'tolak' ? 'danger' : 'warning') }}-lt">
                                                                    {{ strtoupper($status->status_approval) }}
                                                                </span>
                                                            </div>
                                                            @if($status->komentar)
                                                                <div class="text-muted small mt-1">
                                                                    <i class="ti ti-message-2 me-1"></i> {{ $status->komentar }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="col-auto">
                                                    @if($approval->approver && $approval->approver->user_id === auth()->id())
                                                        <x-tabler.button 
                                                            type="button" 
                                                            class="btn-icon btn-sm btn-ghost-danger ajax-delete" 
                                                            data-url="{{ route('pemutu.dokumens.approval.destroy', $approval->encrypted_dokapproval_id) }}" 
                                                            data-title="Hapus Approval?" 
                                                            data-text="Data approval ini akan dihapus permanen."
                                                            icon="ti ti-trash" 
                                                        />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty py-5">
                                    <div class="empty-icon"><i class="ti ti-ghost" style="font-size: 3rem;"></i></div>
                                    <p class="empty-title">Belum ada riwayat approval</p>
                                    <p class="empty-subtitle text-muted">Silahkan ajukan approval untuk melegalkan dokumen ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <script>
                (function() {
                    const dokId = "{{ $dokumen->hashid ?? $dokumen->dok_id }}";
                    const storageKey = `pemutu_doc_detail_subtab_${dokId}`;
                    const tabs = document.querySelectorAll('#doc-detail-tabs .nav-link');
                    
                    // 1. Restore Tab from LocalStorage
                    const savedTabId = localStorage.getItem(storageKey);
                    if (savedTabId) {
                        const targetTab = document.querySelector(`#doc-detail-tabs .nav-link[data-tab-id="${savedTabId}"]`);
                        if (targetTab) {
                            const tabInstance = new bootstrap.Tab(targetTab);
                            tabInstance.show();
                        }
                    }

                    // 2. Save Tab on Change
                    tabs.forEach(tab => {
                        tab.addEventListener('shown.bs.tab', function (e) {
                            const tabId = e.target.dataset.tabId;
                            localStorage.setItem(storageKey, tabId);
                            
                            // Update URL for shareability
                            const url = new URL(window.location);
                            url.searchParams.set('subtab', tabId);
                            window.history.replaceState({}, '', url);
                        });
                    });

                    // 3. Force Immediate Reload on Form/Delete Success
                    $(document).off('ajax-form:success.instantReload').on('ajax-form:success.instantReload', '.ajax-form', function(e, response) {
                       window.location.reload(); 
                    });
                    
                    $(document).off('ajax-delete:success.instantReload').on('ajax-delete:success.instantReload', '.ajax-delete', function() {
                        window.location.reload();
                    });
                })();
            </script>
        </div>
    </div>
    @endsection
@endif
