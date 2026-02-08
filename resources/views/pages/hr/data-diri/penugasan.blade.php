<div class="d-flex justify-content-between align-items-center m-3">
    <h3>Riwayat Penugasan (Struktural & Unit)</h3>
    @if(auth()->user()->can('hr.penugasan.create'))
    <a href="#" class="btn btn-primary ajax-modal-btn" data-url="{{ route('hr.pegawai.penugasan.create', $pegawai->encrypted_pegawai_id) }}" data-modal-title="Tambah Penugasan">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Tambah Penugasan
    </a>
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
                    {{ $item->orgUnit->name ?? '-' }}
                    <div class="text-muted small">{{ ucfirst(str_replace('_', ' ', $item->orgUnit->type ?? '')) }}</div>
                </td>
                <td>{{ $item->tgl_mulai?->format('d M Y') }}</td>
                <td>{{ $item->tgl_selesai?->format('d M Y') ?? '-' }}</td>
                <td>{{ $item->no_sk ?? '-' }}</td>
                <td>
                    @if($item->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Selesai</span>
                    @endif
                </td>
                <td class="text-end">
                    <a href="#" class="btn btn-sm btn-ghost-primary ajax-modal-btn" data-url="{{ route('hr.pegawai.penugasan.edit', [$pegawai->encrypted_pegawai_id, $item->riwayatpenugasan_id]) }}" data-modal-title="Edit Penugasan">
                        Edit
                    </a>
                    <button type="button" class="btn btn-sm btn-ghost-danger ajax-delete" data-url="{{ route('hr.pegawai.penugasan.destroy', [$pegawai->encrypted_pegawai_id, $item->riwayatpenugasan_id]) }}">
                        Hapus
                    </button>
                    @if($item->is_active)
                    <button type="button" class="btn btn-sm btn-ghost-warning ajax-modal-btn" data-url="#" onclick="alert('Fitur End Assignment belum diimplementasikan di modal ini (Gunakan Edit).')">
                        End
                    </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">Belum ada riwayat penugasan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
