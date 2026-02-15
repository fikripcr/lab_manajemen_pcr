@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ route('pemutu.dokumens.index', ['tabs' => (in_array($parent->jenis, ['standar', 'formulir', 'sop', 'manual_prosedur']) ? 'standar' : 'kebijakan')]) }}">Dokumen SPMI</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pemutu.dokumens.show', $parent) }}">{{ $parent->judul }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sub-Dokumen</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    <span class="badge badge-outline text-muted me-2">{{ $dokSub->seq }}</span>
                    {{ $dokSub->judul }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <x-tabler.button href="javascript:history.back()" style="secondary" icon="ti ti-arrow-left" text="Kembali" />
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @php
            $parentJenis = $parent->jenis ? strtolower(trim($parent->jenis)) : '';
            $showIndikators = $dokSub->is_hasilkan_indikator || (in_array($parentJenis, ['renop']) && !$childType);
            
            $tab2Label = '';
            if ($showIndikators) {
                $tab2Label = 'Indikator ' . ucfirst($parentJenis === 'standar' ? 'standar' : 'renop');
            } elseif ($childType) {
                $tab2Label = 'Turunan (' . $childType . ')';
            }

            // Persistence & Default Logic
            $activeSubTab = request()->get('subtab');
            if (!$activeSubTab) {
                $activeSubTab = $tab2Label ? 'indicators' : 'overview';
            }
        @endphp

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" id="sub-doc-tabs">
                    <li class="nav-item">
                        <a href="#tab-overview" class="nav-link {{ $activeSubTab === 'overview' ? 'active' : '' }}" data-bs-toggle="tab" data-tab-id="overview">
                            <i class="ti ti-info-circle me-1"></i> Isi Dokumen
                        </a>
                    </li>
                    @if($tab2Label)
                    <li class="nav-item">
                        <a href="#tab-secondary" class="nav-link {{ $activeSubTab === 'indicators' ? 'active' : '' }}" data-bs-toggle="tab" data-tab-id="indicators">
                            <i class="ti {{ $showIndikators ? 'ti-target' : 'ti-hierarchy-2' }} me-1"></i> {{ $tab2Label }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="tab-content">
                <!-- Tab Overview -->
                <div class="tab-pane {{ $activeSubTab === 'overview' ? 'active show' : '' }}" id="tab-overview">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="card-actions bg-transparent border-0">
                                <x-tabler.button 
                                    href="#" 
                                    class="btn-primary ajax-modal-btn"
                                    data-url="{{ route('pemutu.dok-subs.edit', $dokSub) }}" 
                                    data-modal-title="Edit Isi Dokumen"
                                    icon="ti ti-pencil"
                                    text="Edit Konten" 
                                />
                            </div>
                        </div>
                        @if($dokSub->isi)
                            <div class="markdown p-4 border rounded shadow-sm mb-3" style="min-height: 150px;">
                                {!! $dokSub->isi !!}
                            </div>
                        @else
                            <div class="text-muted text-center py-5 fst-italic border rounded bg-light-lt">
                                Belum ada konten isi. Klik edit untuk menambahkan.
                            </div>
                        @endif
                    </div>
                </div>

                @if($tab2Label)
                <!-- Tab Secondary (Indicators/Documents) -->
                <div class="tab-pane {{ $activeSubTab === 'indicators' ? 'active show' : '' }}" id="tab-secondary">
                    @if($showIndikators)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Daftar {{ $tab2Label }}</h4>
                                <div class="card-actions bg-transparent border-0">
                                    <x-tabler.button href="{{ route('pemutu.indikators.create', ['parent_dok_id' => $parent->hashid, 'doksub_ids[]' => $dokSub->hashid, 'type' => ($parentJenis === 'standar' ? 'standar' : 'renop')]) }}" 
                                       style="success" size="sm" icon="ti ti-plus" text="Tambah Indikator" />
                                </div>
                            </div>
                            <div class="table-responsive">
                                <x-tabler.datatable
                                    id="table-indicators"
                                    route="{{ route('pemutu.indikators.data', ['doksub_id' => $dokSub->hashid]) }}"
                                    ajax-load="true"
                                    :columns="[
                                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                                        ['data' => 'no_indikator', 'name' => 'no_indikator', 'title' => 'No. Indikator', 'width' => '15%'],
                                        ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Indikator'],
                                        ['data' => 'target', 'name' => 'target', 'title' => 'Target'],
                                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '15%']
                                    ]"
                                />
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Daftar {{ $childType }}</h4>
                                <div class="card-actions bg-transparent border-0">
                                    <x-tabler.button href="#" style="primary" size="sm" icon="ti ti-plus" class="ajax-modal-btn" 
                                       data-url="{{ route('pemutu.dokumens.create', ['parent_id' => $parent->hashid, 'parent_doksub_id' => $dokSub->hashid]) }}" 
                                       data-modal-title="Tambah {{ $childType }}" text="Tambah {{ $childType }}" />
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="w-1">No</th>
                                            <th>Judul {{ $childType }}</th>
                                            <th>Kode</th>
                                            <th class="w-1 text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dokSub->childDokumens as $child)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('pemutu.dokumens.show', $child) }}" class="fw-bold text-reset">
                                                        {{ $child->judul }}
                                                    </a>
                                                </td>
                                                <td>{{ $child->kode ?? '-' }}</td>
                                                <td class="text-end">
                                                    <div class="btn-list flex-nowrap justify-content-end">
                                                        <x-tabler.button href="#" style="outline-secondary" size="sm" icon="ti ti-pencil" class="ajax-modal-btn btn-icon" data-url="{{ route('pemutu.dokumens.edit', $child) }}" data-modal-title="Edit {{ $childType }}" />
                                                        <x-tabler.button href="#" style="danger" size="sm" icon="ti ti-trash" class="ajax-delete btn-icon" data-url="{{ route('pemutu.dokumens.destroy', $child) }}" data-title="Hapus?" />
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-5 fst-italic">
                                                    Belum ada data {{ $childType }}.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                @endif
            </div>

            @if(!$tab2Label)
                <div class="card-footer bg-light-lt">
                    <div class="alert alert-info border-0 mb-0">
                        <i class="ti ti-info-circle me-2"></i> Jenis dokumen ini (<strong>{{ strtoupper($parent->jenis) }}</strong>) tidak memiliki turunan dokumen lebih lanjut pada level ini.
                    </div>
                </div>
            @endif
        </div>

        <script>
            (function() {
                const dokId = "{{ $dokSub->hashid ?? $dokSub->doksub_id }}";
                const storageKey = `pemutu_docsub_detail_tab_${dokId}`;
                const tabs = document.querySelectorAll('#sub-doc-tabs .nav-link');
                
                // 1. Restore Tab from LocalStorage
                const savedTabId = localStorage.getItem(storageKey);
                if (savedTabId) {
                    const targetTab = document.querySelector(`#sub-doc-tabs .nav-link[data-tab-id="${savedTabId}"]`);
                    if (targetTab) {
                        const tabInstance = new bootstrap.Tab(targetTab);
                        tabInstance.show();
                    }
                }

                tabs.forEach(tab => {
                    tab.addEventListener('shown.bs.tab', function (e) {
                        const tabId = e.target.dataset.tabId;
                        localStorage.setItem(storageKey, tabId);
                        
                        const url = new URL(window.location);
                        url.searchParams.set('subtab', tabId);
                        window.history.replaceState({}, '', url);
                    });
                });

                // Force Immediate Reload on Form/Delete Success
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
