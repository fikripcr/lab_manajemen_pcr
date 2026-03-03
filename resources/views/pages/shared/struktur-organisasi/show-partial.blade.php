<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Unit: {{ $orgUnit->name }}</h3>
        <div class="card-actions">
            <a href="{{ route('shared.struktur-organisasi.show', $orgUnit->encrypted_org_unit_id) }}" class="btn btn-sm btn-outline-primary">
                Selengkapnya
            </a>
        </div>
    </div>
    <div class="card-body">
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
    </div>
</div>
