@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('content')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ route('pemutu.dokumens.index', ['tabs' => (in_array($dokumen->jenis, ['standar', 'formulir', 'manual_prosedur']) ? 'standar' : 'kebijakan')]) }}">Dokumen SPMI</a></li>
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
                        <a href="{{ route('pemutu.dokumens.show-renop-with-indicators', $dokumen->dok_id) }}" class="btn btn-primary d-none d-sm-inline-block">
                            <i class="ti ti-chart-bar me-2"></i> Akumulasi Indikator
                        </a>
                    @endif
                    <a href="{{ route('pemutu.dokumens.index', ['tabs' => (in_array($dokumen->jenis, ['standar', 'formulir', 'manual_prosedur']) ? 'standar' : 'kebijakan')]) }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
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
        @endphp

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title text-uppercase">Manajemen Dokumen {{ $dokumen->jenis }}</h3>
                <div class="card-actions">
                    <div class="btn-group" role="group">
                        <a href="#" class="btn btn-outline-secondary ajax-modal-btn" data-url="{{ route('pemutu.dokumens.edit', $dokumen->dok_id) }}" data-modal-title="Edit Dokumen">
                            <i class="ti ti-pencil me-1"></i> Edit
                        </a>
                        @if($isDokSubBased)
                            <a href="#" class="btn btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dok-subs.create', ['dok_id' => $dokumen->dok_id]) }}" data-modal-title="Tambah {{ $childLabel }}">
                                <i class="ti ti-plus me-1"></i> {{ $childLabel }}
                            </a>
                        @else
                            <a href="#" class="btn btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dokumens.create', ['parent_id' => $dokumen->dok_id]) }}" data-modal-title="Tambah {{ $childLabel }}">
                                <i class="ti ti-plus me-1"></i> {{ $childLabel }}
                            </a>
                        @endif
                        <a href="#" class="btn btn-outline-danger ajax-delete" data-url="{{ route('pemutu.dokumens.destroy', $dokumen->dok_id) }}" data-title="Hapus Dokumen?" data-text="Dokumen ini beserta sub-dokumennya akan dihapus permanen.">
                            <i class="ti ti-trash me-1"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar {{ $childLabel }}</h4>
            </div>
            <div class="card-body p-0">
                <x-tabler.datatable
                    id="table-sub-dokumen"
                    route="{{ route('pemutu.dokumens.children-data', $dokumen->dok_id) }}"
                    ajax-load="true"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false,'class'=>'text-center'],
                        ['data' => 'judul', 'name' => 'judul', 'title' => 'Judul '.$childLabel],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '15%']
                    ]"
                />
            </div>
        </div>
    </div>
</div>
@endsection
