<div class="d-flex justify-content-between align-items-center mb-3 mt-3">
    <h3 class="mb-0">Riwayat Status Pegawai</h3>
    <x-tabler.button 
        style="primary" 
        class="ajax-modal-btn" 
        data-url="{{ route('hr.pegawai.status-pegawai.create', $pegawai->encrypted_pegawai_id) }}" 
        data-modal-title="Ubah Status Pegawai"
        icon="ti ti-edit"
        text="Ubah Status" />
</div>
<div class="card mb-3">
    <div class="card-table">
        <x-tabler.datatable-client
            id="table-status-pegawai-list"
            :columns="[
                ['name' => 'Status'],
                ['name' => 'TMT'],
                ['name' => 'No. SK'],
                ['name' => 'File SK'],
                ['name' => 'Status Aktif'],
                ['name' => 'Status Approval']
            ]"
        >
            @forelse($pegawai->historyStatPegawai as $item)
            <tr>
                <td>
                    <span class="badge bg-blue-lt">{{ $item->statusPegawai->nama_status ?? ($item->statusPegawai->statpegawai ?? '-') }}</span>
                </td>
                <td>{{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}</td>
                <td>{{ $item->no_sk ?? '-' }}</td>
                <td>
                    @if($item->file_sk)
                        <x-tabler.button href="{{ asset($item->file_sk) }}" style="ghost-info" class="btn-sm" icon="ti ti-download" target="_blank" text="Unduh" />
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($pegawai->latest_riwayatstatpegawai_id == $item->riwayatstatpegawai_id)
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
            </tr>
            @empty
                {{-- Empty handled by component --}}
            @endforelse
        </x-tabler.datatable-client>
    </div>
</div>
