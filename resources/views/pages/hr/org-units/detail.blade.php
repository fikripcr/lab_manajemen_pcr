<div class="card mb-3">
    <div class="card-header">
        <div>
            <div class="d-flex align-items-center">
                <h3 class="card-title mb-0 me-2">{{ $orgUnit->name }}</h3>
                @if($orgUnit->code)
                    <span class="badge bg-secondary-lt me-2">{{ $orgUnit->code }}</span>
                @endif
                @if($orgUnit->type)
                    <span class="badge bg-blue-lt">{{ $orgUnit->type }}</span>
                @endif
            </div>
            <div class="text-muted small mt-1">
                @if($orgUnit->parent)
                    Berada dibawah: <a href="#" class="tree-item-link" data-url="{{ route('hr.org-units.show', $orgUnit->parent_id) }}">{{ $orgUnit->parent->name }}</a>
                @endif
            </div>
        </div>
        <div class="card-actions">
            <div class="btn-group" role="group">
                <a href="#" class="btn btn-outline-secondary ajax-modal-btn" data-url="{{ route('hr.org-units.edit', $orgUnit->org_unit_id) }}" data-modal-title="Edit Unit">
                    <i class="ti ti-pencil"></i>
                </a>
                <a href="#" class="btn btn-outline-primary ajax-modal-btn" data-url="{{ route('hr.org-units.create', ['parent_id' => $orgUnit->org_unit_id]) }}" data-modal-title="Add Sub-Unit">
                    <i class="ti ti-plus me-2"></i> Sub-Unit
                </a>
                <a href="#" class="btn btn-outline-danger ajax-delete" data-url="{{ route('hr.org-units.destroy', $orgUnit->org_unit_id) }}" data-title="Hapus Unit?" data-text="Unit ini akan dihapus.">
                    <i class="ti ti-trash"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h4 class="card-title mb-0">Pegawai Ditugaskan</h4>
            <a href="#" class="btn btn-sm btn-outline-primary ajax-modal-btn" data-url="{{ route('hr.pegawai.penugasan.create', ['pegawai' => 0]) }}?org_unit_id={{ $orgUnit->org_unit_id }}" data-modal-title="Tambah Penugasan">
                <i class="ti ti-user-plus me-1"></i> Add Personil
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Tgl Mulai</th>
                        <th>Status</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orgUnit->riwayatPenugasan ?? [] as $penugasan)
                        <tr>
                            <td>
                                <a href="{{ route('hr.pegawai.show', $penugasan->pegawai_id) }}">{{ $penugasan->pegawai->nama ?? '-' }}</a>
                            </td>
                            <td>{{ $penugasan->pegawai->nip ?? '-' }}</td>
                            <td>{{ $penugasan->tgl_mulai?->format('d M Y') }}</td>
                            <td>
                                @if($penugasan->is_active)
                                    <span class="badge bg-success text-white">Aktif</span>
                                @else
                                    <span class="badge bg-secondary text-white">Selesai</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-ghost-danger ajax-delete" data-url="{{ route('hr.pegawai.penugasan.destroy', [$penugasan->pegawai_id, $penugasan->riwayatpenugasan_id]) }}">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada pegawai ditugaskan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
