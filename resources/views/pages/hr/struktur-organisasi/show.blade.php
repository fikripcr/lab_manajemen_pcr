<x-tabler.card class="border-0 shadow-sm">
    <x-tabler.card-header class="bg-light-subtle py-3">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-md bg-primary-lt me-3">
                <i class="ti ti-sitemap fs-2"></i>
            </div>
            <div>
                <h3 class="card-title mb-0">{{ $orgUnit->name }}</h3>
                <span class="text-muted small fw-normal">{{ $orgUnit->code ?? 'No Code' }}</span>
            </div>
        </div>
        <x-slot:actions>
            <div class="dropdown">
                <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots-vertical fs-3"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-sm">
                    <a class="dropdown-item ajax-modal-btn" href="#" 
                       data-url="{{ route('hr.struktur-organisasi.edit', $orgUnit->encrypted_org_unit_id) }}"
                       data-modal-title="Edit Unit: {{ $orgUnit->name }}">
                        <i class="ti ti-edit me-2"></i> Edit unit
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger ajax-delete" href="javascript:void(0)"
                       data-url="{{ route('hr.struktur-organisasi.destroy', $orgUnit->encrypted_org_unit_id) }}"
                       data-title="Hapus Unit"
                       data-text="Apakah Anda yakin ingin menghapus unit '{{ $orgUnit->name }}'? Pekerjaan ini tidak dapat dibatalkan.">
                        <i class="ti ti-trash me-2"></i> Hapus unit
                    </a>
                </div>
            </div>
        </x-slot:actions>
    </x-tabler.card-header>
    <x-tabler.card-body class="p-4">
        {{-- Main Info Section --}}
        <h4 class="subheader mb-2 text-primary">Informasi Utama</h4>
        <div class="datagrid datagrid-cols-2 mb-3">
            
            <div class="datagrid-item">
                <div class="datagrid-title"><i class="ti ti-id-badge me-1"></i> Kode</div>
                <div class="datagrid-content text-monospace">{{ $orgUnit->code ?? '-' }}</div>
            </div>
            
            <div class="datagrid-item">
                <div class="datagrid-title"><i class="ti ti-layers-intersect me-1"></i> Tipe</div>
                <div class="datagrid-content">
                    <span class="badge bg-secondary-lt">{{ str_replace('_', ' ', ucfirst($orgUnit->type)) }}</span>
                </div>
            </div>

            <div class="datagrid-item">
                <div class="datagrid-title"><i class="ti ti-hierarchy-2 me-1"></i> Parent Unit</div>
                <div class="datagrid-content text-secondary small">{{ $orgUnit->parent->name ?? 'Root' }}</div>
            </div>

            <div class="datagrid-item">
                <div class="datagrid-title"><i class="ti ti-chart-arrows me-1"></i> Level</div>
                <div class="datagrid-content">
                    <span class="avatar avatar-xs bg-azure-lt">{{ $orgUnit->level }}</span>
                </div>
            </div>

            <div class="datagrid-item">
                <div class="datagrid-title"><i class="ti ti-toggle-left me-1"></i> Status</div>
                <div class="datagrid-content">
                    @if($orgUnit->is_active)
                        <span class="status status-success">
                            <span class="status-dot status-dot-animated"></span>
                            Aktif
                        </span>
                    @else
                        <span class="status status-danger">Inactive</span>
                    @endif
                </div>
            </div>
        </div>

        @if($orgUnit->description)
        <div class="mb-4">
            <div class="datagrid-title mb-1"><i class="ti ti-note me-1"></i> Deskripsi</div>
            <div class="text-secondary small">{{ $orgUnit->description }}</div>
        </div>
        @endif

        {{-- Sub-Unit (Children) --}}
        @if($orgUnit->children->isNotEmpty())
            <hr class="my-4 op-2">
            <h4 class="subheader mb-3 text-danger"><i class="ti ti-subtask me-1"></i> Sub-Unit ({{ $orgUnit->children->count() }})</h4>
            <div class="list-group list-group-flush border rounded-2 overflow-hidden mb-2 shadow-sm">
                @foreach($orgUnit->children as $child)
                    <div class="list-group-item list-group-item-action py-2">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-xs bg-red-lt rounded">
                                    <i class="ti ti-sitemap"></i>
                                </span>
                            </div>
                            <div class="col text-truncate">
                                <a href="javascript:void(0)" 
                                   onclick="loadUnitDetail('{{ $child->encrypted_org_unit_id }}', '{{ route('hr.struktur-organisasi.show', $child) }}')" 
                                   class="text-reset d-block fw-bold small text-truncate">
                                    {{ $child->name }}
                                </a>
                                <div class="text-muted small text-truncate mt-n1" style="font-size: 10px;">{{ $child->code ?? '-' }} • {{ str_replace('_', ' ', $child->type) }}</div>
                            </div>
                            <div class="col-auto">
                                @if($child->is_active)
                                    <span class="status-dot status-dot-animated status-green" title="Aktif"></span>
                                @else
                                    <span class="status-dot status-red" title="Nonaktif"></span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- PIC / Auditee --}}
        @if($orgUnit->auditee)
            <hr class="my-4 op-2">
            <h4 class="subheader mb-3 text-azure">PIC / Auditee</h4>
            <div class="d-flex align-items-center bg-azure-lt p-2 rounded-2">
                <span class="avatar avatar-md me-3 rounded" style="background-image: url('{{ $orgUnit->auditee->avatar_url }}')"></span>
                <div>
                    <div class="fw-bold">{{ $orgUnit->auditee->name }}</div>
                    <div class="text-muted small">{{ $orgUnit->auditee->email }}</div>
                </div>
            </div>
        @endif

        {{-- Personil List --}}
        @if($orgUnit->personils->isNotEmpty())
            <hr class="my-4 op-2">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="subheader mb-0 text-indigo">Personil Tersertifikasi</h4>
                <span class="badge bg-indigo-lt">{{ $orgUnit->personils->count() }} orang</span>
            </div>
            <div class="list-group list-group-flush border rounded-2 overflow-hidden">
                @foreach($orgUnit->personils as $p)
                    <div class="list-group-item list-group-item-action py-2">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-sm rounded-circle" style="background-image: url('{{ $p->user->avatar_url ?? '' }}')"></span>
                            </div>
                            <div class="col text-truncate">
                                <div class="text-reset d-block fw-semibold text-truncate">{{ $p->user->name ?? $p->nip }}</div>
                                <div class="text-muted small text-truncate mt-n1">{{ $p->posisi }}</div>
                            </div>
                            @if($p->successor_id)
                                <div class="col-auto">
                                    <span class="badge bg-warning-lt" title="Memiliki Pengganti">
                                        <i class="ti ti-user-plus fs-4"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($orgUnit->successor)
        <div class="mt-4 p-3 border border-dashed rounded bg-yellow-lt">
             <div class="datagrid-title mb-2 text-warning"><i class="ti ti-user-check me-1"></i> Unit Pengganti / Penerus</div>
             <a href="#" class="tree-item-link fw-bold text-dark" data-id="{{ $orgUnit->successor->encrypted_org_unit_id }}" data-url="{{ route('hr.struktur-organisasi.show', $orgUnit->successor) }}">
                {{ $orgUnit->successor->name }}
             </a>
        </div>
        @endif
    </x-tabler.card-body>
</x-tabler.card>
