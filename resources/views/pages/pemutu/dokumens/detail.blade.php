@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

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

<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ route('pemutu.dokumens.index', ['tabs' => (in_array($parentJenis, ['standar', 'formulir', 'manual_prosedur']) ? 'standar' : 'kebijakan')]) }}">Dokumen SPMI</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </div>
                <h2 class="page-title d-flex align-items-center flex-wrap gap-2">
                    @if($dokumen->jenis)
                        <span class="badge badge-outline text-blue bg-blue-lt" style="font-size: 0.75rem;">{{ strtoupper($dokumen->jenis) }}</span>
                    @endif
                    @if($dokumen->kode)
                        <span class="badge badge-outline text-muted" style="font-size: 0.75rem;" title="Kode Dokumen">{{ $dokumen->kode }}</span>
                    @endif
                    @if($dokumen->periode)
                        <span class="badge badge-outline text-muted" style="font-size: 0.75rem;" title="Periode">{{ $dokumen->periode }}</span>
                    @endif
                    <div class="w-100 mt-1">{{ $dokumen->judul }}</div>
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    @if($dokumen->jenis === 'renop')
                        <a href="{{ route('pemutu.dokumens.show-renop-with-indicators', $dokumen) }}" class="btn btn-primary d-none d-sm-inline-block">
                            <i class="ti ti-chart-bar me-2"></i> Akumulasi Indikator
                        </a>
                    @endif
                    
                    <div class="btn-group shadow-sm" role="group">
                        <a href="#" class="btn btn-white ajax-modal-btn" data-url="{{ route('pemutu.dokumens.edit', $dokumen) }}" data-modal-title="Edit Dokumen">
                            <i class="ti ti-pencil me-1"></i> Edit
                        </a>
                        @if($isDokSubBased)
                            <a href="#" class="btn btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dok-subs.create', ['dok_id' => $dokumen->hashid]) }}" data-modal-title="Tambah {{ $childLabel }}">
                                <i class="ti ti-plus me-1"></i> {{ $childLabel }}
                            </a>
                        @else
                            <a href="#" class="btn btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dokumens.create', ['parent_id' => $dokumen->hashid]) }}" data-modal-title="Tambah {{ $childLabel }}">
                                <i class="ti ti-plus me-1"></i> {{ $childLabel }}
                            </a>
                        @endif
                        <a href="#" class="btn btn-outline-danger ajax-delete" data-url="{{ route('pemutu.dokumens.destroy', $dokumen) }}" data-title="Hapus Dokumen?" data-text="Dokumen ini beserta sub-dokumennya akan dihapus permanen.">
                            <i class="ti ti-trash me-1"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">

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
                            <div class="markdown p-3 border rounded bg-white shadow-sm" style="min-height: 100px; color: var(--tblr-body-color);">
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
                        <div class="row">
                            <div class="col-md-4 border-end">
                                <h4 class="card-title mb-3">Submit Approval</h4>
                                <form action="{{ route('pemutu.dokumens.approve', $dokumen) }}" method="POST" class="ajax-form" data-success-callback="location.reload()">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label required">Approver (Personil)</label>
                                        <select name="approver_id" class="form-select select2" required>
                                            <option value="">Pilih Personil...</option>
                                            @foreach($personils as $p)
                                                <option value="{{ $p->personil_id }}">{{ $p->nama }} ({{ $p->jenis ?? '-' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Status Approval</label>
                                        <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                            <label class="form-selectgroup-item flex-fill">
                                                <input type="radio" name="status" value="terima" class="form-selectgroup-input" checked>
                                                <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                    <div class="me-3">
                                                        <span class="form-selectgroup-check"></span>
                                                    </div>
                                                    <div>
                                                        <span class="d-block text-success font-weight-bold">Terima</span>
                                                        <small class="d-block text-muted">Dokumen disetujui / dilegalkan</small>
                                                    </div>
                                                </div>
                                            </label>
                                            <label class="form-selectgroup-item flex-fill mt-2">
                                                <input type="radio" name="status" value="tolak" class="form-selectgroup-input">
                                                <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                    <div class="me-3">
                                                        <span class="form-selectgroup-check"></span>
                                                    </div>
                                                    <div>
                                                        <span class="d-block text-danger font-weight-bold">Tolak</span>
                                                        <small class="d-block text-muted">Dokumen ditolak dengan alasan tertentu</small>
                                                    </div>
                                                </div>
                                            </label>
                                            <label class="form-selectgroup-item flex-fill mt-2">
                                                <input type="radio" name="status" value="tangguhkan" class="form-selectgroup-input">
                                                <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                    <div class="me-3">
                                                        <span class="form-selectgroup-check"></span>
                                                    </div>
                                                    <div>
                                                        <span class="d-block text-warning font-weight-bold">Tangguhkan</span>
                                                        <small class="d-block text-muted">Butuh perbaikan sebelum disetujui</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Komentar / Catatan</label>
                                        <textarea name="komentar" class="form-control" rows="3" placeholder="Masukkan komentar jika ada..."></textarea>
                                    </div>
                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="ti ti-device-floppy me-2"></i> Simpan Approval
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-8">
                                <h4 class="card-title mb-3">Riwayat Approval</h4>
                                @if($dokumen->approvals->count() > 0)
                                    <div class="divide-y">
                                        @foreach($dokumen->approvals as $approval)
                                            <div class="py-3">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="avatar avatar-sm rounded">{{ substr($approval->approver->nama ?? '?', 0, 1) }}</span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-bold">
                                                            {{ $approval->approver->nama ?? 'Unknown' }}
                                                            <small class="text-muted ms-2">{{ $approval->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <div class="text-muted small">{{ $approval->proses }} @if($approval->jabatan) ({{ $approval->jabatan }}) @endif</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        @php
                                                            $lastStatus = $approval->statuses->first();
                                                            $statusBadge = match($lastStatus->status_approval ?? '') {
                                                                'terima' => 'success',
                                                                'tolak' => 'danger',
                                                                'tangguhkan' => 'warning',
                                                                default => 'secondary'
                                                            };
                                                        @endphp
                                                        <span class="badge bg-{{ $statusBadge }}-lt">{{ strtoupper($lastStatus->status_approval ?? 'PENDING') }}</span>
                                                    </div>
                                                </div>
                                                @if($lastStatus && $lastStatus->komentar)
                                                    <div class="mt-2 p-2 bg-light-lt rounded small border">
                                                        <i class="ti ti-message-2 me-1"></i> {{ $lastStatus->komentar }}
                                                    </div>
                                                @endif
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
            </div>
        </div>

        <script>
            (function() {
                const tabs = document.querySelectorAll('#doc-detail-tabs .nav-link');
                tabs.forEach(tab => {
                    tab.addEventListener('shown.bs.tab', function (e) {
                        const tabId = e.target.dataset.tabId;
                        const url = new URL(window.location);
                        url.searchParams.set('subtab', tabId);
                        window.history.replaceState({}, '', url);
                    });
                });
            })();
        </script>
    </div>
</div>
@endsection
