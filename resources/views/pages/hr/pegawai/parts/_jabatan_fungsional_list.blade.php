<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h3 class="mb-0">Riwayat Jabatan Fungsional</h3>
    <x-tabler.button 
        style="primary" 
        class="ajax-modal-btn" 
        data-url="{{ route('hr.pegawai.jabatan-fungsional.create', $pegawai->encrypted_pegawai_id) }}" 
        data-modal-title="Ubah Jabatan Fungsional"
        icon="ti ti-edit"
        text="Ubah Jafung" />
</div>
<div class="card mb-3">
    <div class="card-table">
        <x-tabler.datatable-client
            id="table-jabfungsional-list"
            :columns="[
                ['name' => 'Jabatan'],
                ['name' => 'TMT'],
                ['name' => 'No. SK'],
                ['name' => 'Status Aktif'],
                ['name' => 'Status Approval']
            ]"
        >
            @forelse($pegawai->historyJabFungsional as $item)
            <tr>
                <td>
                    <span class="badge bg-indigo-lt">{{ $item->jabatanFungsional->jabfungsional ?? '-' }}</span>
                </td>
                <td>{{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}</td>
                <td>{{ $item->no_sk_internal ?? $item->no_sk_kopertis ?? '-' }}</td>
                <td>
                    @if($pegawai->latest_riwayatjabfungsional_id == $item->riwayatjabfungsional_id)
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
