<div class="d-flex justify-content-between align-items-center m-3">
    <h3 class="mb-0">Penugasan (Struktural & Unit)</h3>
    @if(auth()->user()->can('hr.penugasan.create'))
    <x-tabler.button 
        style="primary" 
        class="ajax-modal-btn" 
        data-url="{{ route('hr.pegawai.penugasan.create', $pegawai->encrypted_pegawai_id) }}" 
        data-modal-title="Tambah Penugasan"
        icon="ti ti-plus"
        text="Tambah Penugasan" />
    @endif
</div>
<div class="card-table">
    <x-tabler.datatable-client
        id="table-penugasan"
        :columns="[
            ['name' => 'Unit / Jabatan'],
            ['name' => 'Tgl Mulai'],
            ['name' => 'Tgl Selesai'],
            ['name' => 'No. SK'],
            ['name' => 'Status'],
            ['name' => 'Aksi', 'className' => 'text-end', 'sortable' => false]
        ]"
    >
        @forelse($pegawai->historyPenugasan ?? [] as $item)
        <tr>
            <td>
                <div class="fw-bold">{{ $item->orgUnit->name ?? '-' }}</div>
                <div class="text-muted small">{{ ucfirst(str_replace('_', ' ', $item->orgUnit->type ?? '')) }}</div>
            </td>
            <td>{{ $item->tgl_mulai?->format('d M Y') }}</td>
            <td>{{ $item->tgl_selesai?->format('d M Y') ?? '-' }}</td>
            <td>{{ $item->no_sk ?? '-' }}</td>
            <td>
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
                        data-url="{{ route('hr.pegawai.penugasan.edit', [$pegawai->encrypted_pegawai_id, $item->encrypted_riwayatpenugasan_id]) }}" 
                        data-modal-title="Edit Penugasan"
                        icon="ti ti-edit"
                        title="Edit" />
                    
                    <x-tabler.button 
                        style="ghost-danger" 
                        class="btn-icon ajax-delete" 
                        data-url="{{ route('hr.pegawai.penugasan.destroy', [$pegawai->encrypted_pegawai_id, $item->encrypted_riwayatpenugasan_id]) }}"
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
            {{-- Empty handled by component --}}
        @endforelse
    </x-tabler.datatable-client>
</div>
