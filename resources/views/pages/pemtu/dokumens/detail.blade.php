    <div class="card-header d-block">
        <div class="d-flex justify-content-between align-items-start">
            <div class="w-100 me-3">
                <div class="d-flex align-items-center mb-2">
                    @if($dokumen->jenis)
                        <span class="badge badge-outline text-blue bg-blue-lt me-2" style="font-size: 0.9rem;">{{ strtoupper($dokumen->jenis) }}</span>
                    @endif
                    @if($dokumen->kode)
                        <span class="badge badge-outline text-muted me-2" title="Kode Dokumen">{{ $dokumen->kode }}</span>
                    @endif
                    @if($dokumen->periode)
                        <span class="badge badge-outline text-muted" title="Periode">{{ $dokumen->periode }}</span>
                    @endif
                </div>
                
                <h3 class="card-title mb-1" style="line-height: 1.5;">{{ $dokumen->judul }}</h3>
            </div>
            
            @php
                $childLabel = 'Sub Dokumen';
                $parentJenis = $dokumen->jenis ? strtolower(trim($dokumen->jenis)) : '';
                
                if($parentJenis) {
                    $childLabel = match($parentJenis) {
                        'visi' => 'Misi',
                        'misi' => 'RPJP',
                        'rjp' => 'Renstra',
                        'renstra' => 'Renop',
                        'renop' => 'Standar/Formulir',
                        'standar' => 'Sub Standar',
                        default => 'Sub Dokumen'
                    };
                }
            @endphp
            
            <div class="card-actions flex-shrink-0">
                <div class="btn-group" role="group">
                    <a href="#" class="btn btn-outline-secondary ajax-modal-btn" data-url="{{ route('pemtu.dokumens.edit', $dokumen->dok_id) }}" data-modal-title="Edit Dokumen">
                        <i class="ti ti-pencil"></i>
                    </a>
                    <a href="#" class="btn btn-outline-primary ajax-modal-btn" data-url="{{ route('pemtu.dokumens.create', ['parent_id' => $dokumen->dok_id]) }}" data-modal-title="Tambah {{ $childLabel }}">
                        <i class="ti ti-plus me-2"></i> {{ $childLabel }}
                    </a>
                    <a href="#" class="btn btn-outline-danger ajax-delete" data-url="{{ route('pemtu.dokumens.destroy', $dokumen->dok_id) }}" data-title="Hapus Dokumen?" data-text="Dokumen ini beserta sub-dokumennya akan dihapus permanen.">
                        <i class="ti ti-trash"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">List {{ $childLabel }} (Anak)</h4>
    </div>
    <div class="card-body p-0">
        <x-tabler.datatable
            id="table-sub-dokumen"
            route="{{ route('pemtu.dokumens.children-data', $dokumen->dok_id) }}"
            ajax-load="true"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                ['data' => 'judul', 'name' => 'judul', 'title' => 'Judul Sub Dokumen'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '15%']
            ]"
        />
    </div>
</div>
