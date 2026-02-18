<div class="d-flex justify-content-between align-items-center mb-3 mt-3">
    <h3 class="mb-0">Riwayat Inpassing</h3>
    <x-tabler.button 
        style="primary" 
        class="ajax-modal-btn" 
        data-url="{{ route('hr.pegawai.inpassing.create', $pegawai->encrypted_pegawai_id) }}" 
        data-modal-title="Tambah Inpassing"
        icon="ti ti-plus"
        text="Tambah" />
</div>
<div class="card mb-3">
    <div class="card-table">
        <x-tabler.datatable-client
            id="table-inpassing-list"
            :columns="[
                ['name' => 'Golongan'],
                ['name' => 'No SK'],
                ['name' => 'TMT'],
                ['name' => 'Masa Kerja'],
                ['name' => 'Gaji Pokok'],
                ['name' => 'File', 'sortable' => false],
                ['name' => 'Status Aktif'],
                ['name' => 'Status Approval'],
                ['name' => 'Aksi', 'className' => 'text-end', 'sortable' => false]
            ]"
        >
            @forelse($pegawai->historyInpassing as $item)
            <tr>
                <td>
                    <div class="fw-bold">{{ $item->golonganInpassing->golongan ?? '-' }}</div>
                    <div class="text-muted small">{{ $item->golonganInpassing->nama_pangkat ?? '-' }}</div>
                </td>
                <td>
                    {{ $item->no_sk }}
                    <div class="text-muted small">{{ $item->tgl_sk ? $item->tgl_sk->format('d M Y') : '' }}</div>
                </td>
                <td>{{ $item->tmt ? $item->tmt->format('d M Y') : '-' }}</td>
                <td>{{ $item->masa_kerja_tahun ?? 0 }} Th {{ $item->masa_kerja_bulan ?? 0 }} Bln</td>
                <td>Rp {{ number_format($item->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                <td>
                    @if($item->file_sk)
                        <x-tabler.button href="{{ asset($item->file_sk) }}" style="ghost-info" class="btn-sm" icon="ti ti-download" target="_blank" title="Unduh SK" text="Unduh" />
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($pegawai->latest_riwayatinpassing_id == $item->riwayatinpassing_id)
                        <span class="badge bg-success text-success-fg">Aktif Saat Ini</span>
                    @else
                        <span class="badge bg-secondary text-secondary-fg">Riwayat</span>
                    @endif
                </td>
                <td>
                    @if($item->approval)
                        {!! getApprovalBadge($item->approval->status) !!}
                    @else
                         -
                    @endif
                </td>
                <td class="text-end">
                    <div class="btn-list justify-content-end">
                        <x-tabler.button 
                            style="ghost-primary" 
                            class="btn-icon ajax-modal-btn" 
                            data-url="{{ route('hr.pegawai.inpassing.edit', ['pegawai' => $pegawai->encrypted_pegawai_id, 'inpassing' => $item->riwayatinpassing_id]) }}" 
                            data-modal-title="Edit Inpassing"
                            icon="ti ti-edit"
                            title="Edit" />
                        
                        <x-tabler.button 
                            style="ghost-danger" 
                            class="btn-icon ajax-delete" 
                            data-url="{{ route('hr.pegawai.inpassing.destroy', ['pegawai' => $pegawai->encrypted_pegawai_id, 'inpassing' => $item->riwayatinpassing_id]) }}" 
                            icon="ti ti-trash"
                            title="Hapus" />
                    </div>
                </td>
            </tr>
            @empty
                {{-- Empty handled by component --}}
            @endforelse
        </x-tabler.datatable-client>
    </div>
</div>
