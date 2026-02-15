<div class="d-flex justify-content-between align-items-center m-3">
    <h3 class="mb-0">Riwayat Penugasan (Struktural & Unit)</h3>
    @if(auth()->user()->can('hr.penugasan.create'))
    <x-tabler.button 
        style="primary" 
        class="ajax-modal-btn" 
        data-url="{{ route('hr.pegawai.penugasan.create', $pegawai->encrypted_pegawai_id) }}" 
        data-modal-title="Tambah Penugasan"
        icon="ti ti-plus">
        Tambah Penugasan
    </x-tabler.button>
    @endif
</div>
<div class="table-responsive">
    <table class="table table-vcenter card-table table-striped">
        <thead>
            <tr>
                <th>Unit / Jabatan</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>No. SK</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pegawai->historyPenugasan ?? [] as $item)
            <tr>
                <td>
                    <div class="fw-bold">{{ $item->orgUnit->name ?? '-' }}</div>
                    <div class="text-muted small">{{ ucfirst(str_replace('_', ' ', $item->orgUnit->type ?? '')) }}</div>
                </td>
                <td>{{ $item->tgl_mulai?->format('d M Y') }}</td>
                <td>{{ $item->tgl_selesai?->format('d M Y') ?? '-' }}</td>
                <td>{{ $item->no_sk ?? '-' }}</td>
                    @if($pegawai->latest_riwayatpenugasan_id == $item->riwayatpenugasan_id)
                        <span class="badge bg-success text-success-fg">Aktif</span>
                    @else
                        <span class="badge bg-secondary text-secondary-fg">Selesai</span>
                    @endif
                </td>
                <td class="text-end">
                    <div class="btn-list justify-content-end">
                        <x-tabler.button 
                            style="ghost-primary" 
                            class="btn-icon ajax-modal-btn" 
                            data-url="{{ route('hr.pegawai.penugasan.edit', [$pegawai->encrypted_pegawai_id, $item->riwayatpenugasan_id]) }}" 
                            data-modal-title="Edit Penugasan"
                            icon="ti ti-edit"
                            title="Edit" />
                        
                        <x-tabler.button 
                            style="ghost-danger" 
                            class="btn-icon ajax-delete" 
                            data-url="{{ route('hr.pegawai.penugasan.destroy', [$pegawai->encrypted_pegawai_id, $item->riwayatpenugasan_id]) }}"
                            icon="ti ti-trash"
                            title="Hapus" />

                        @if($item->is_active)
                        <x-tabler.button 
                            style="ghost-warning" 
                            class="btn-icon" 
                            onclick="alert('Fitur End Assignment dilakukan melalui Edit.')"
                            icon="ti ti-calendar-off"
                            title="End Assignment" />
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">Belum ada riwayat penugasan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
