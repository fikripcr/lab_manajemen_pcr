<x-tabler.card>
    <x-tabler.card-header title="{{ $orgUnit->name }}">
        <x-slot:actions>
            <div class="dropdown">
                <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots-vertical fs-3"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item ajax-modal-btn" href="#" 
                       data-url="{{ route('hr.struktur-organisasi.edit', $orgUnit->encrypted_org_unit_id) }}"
                       data-modal-title="Edit Unit: {{ $orgUnit->name }}">
                        <i class="ti ti-edit me-2"></i> Edit
                    </a>
                    <a class="dropdown-item text-danger ajax-delete" href="javascript:void(0)"
                       data-url="{{ route('hr.struktur-organisasi.destroy', $orgUnit->encrypted_org_unit_id) }}"
                       data-title="Hapus Unit"
                       data-text="Apakah Anda yakin ingin menghapus unit '{{ $orgUnit->name }}'? Pekerjaan ini tidak dapat dibatalkan.">
                        <i class="ti ti-trash me-2"></i> Hapus
                    </a>
                </div>
            </div>
        </x-slot:actions>
    </x-tabler.card-header>
    <x-tabler.card-body>
        <div class="datagrid">
            <div class="datagrid-item">
                <div class="datagrid-title">Nama Unit</div>
                <div class="datagrid-content">{{ $orgUnit->name }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Kode</div>
                <div class="datagrid-content">{{ $orgUnit->code ?? '-' }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Tipe</div>
                <div class="datagrid-content">{{ ucfirst($orgUnit->type) }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Parent Unit</div>
                <div class="datagrid-content">{{ $orgUnit->parent->name ?? '-' }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Level</div>
                <div class="datagrid-content">{{ $orgUnit->level }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Status</div>
                <div class="datagrid-content">
                    @if($orgUnit->is_active)
                        <span class="status status-success">Aktif</span>
                    @else
                        <span class="status status-danger">Tidak Aktif</span>
                    @endif
                </div>
            </div>
        </div>

        @if($orgUnit->auditee)
            <div class="mt-4">
                <div class="datagrid">
                    <div class="datagrid-item">
                        <div class="datagrid-title">Auditee / PIC</div>
                        <div class="datagrid-content">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-xs me-2" style="background-image: url('{{ $orgUnit->auditee->avatar_url }}')"></span>
                                {{ $orgUnit->auditee->name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($orgUnit->personils->isNotEmpty())
            <div class="mt-4">
                <h4 class="mb-2">Personil ({{ $orgUnit->personils->count() }})</h4>
                <div class="list-group list-group-flush">
                    @foreach($orgUnit->personils as $p)
                        <div class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-xs" style="background-image: url('{{ $p->user->avatar_url ?? '' }}')"></span>
                                </div>
                                <div class="col text-truncate">
                                    <span class="text-body d-block">{{ $p->user->name ?? $p->nip }}</span>
                                    <small class="text-muted d-block text-truncate mt-n1">{{ $p->posisi }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </x-tabler.card-body>
</x-tabler.card>
