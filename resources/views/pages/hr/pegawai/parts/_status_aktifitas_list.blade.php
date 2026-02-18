<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h3 class="mb-0">Riwayat Status Aktifitas</h3>
    <x-tabler.button 
        style="primary" 
        class="ajax-modal-btn" 
        data-url="{{ route('hr.pegawai.status-aktifitas.create', $pegawai->encrypted_pegawai_id) }}" 
        data-modal-title="Ubah Status Aktifitas"
        icon="ti ti-edit"
        text="Ubah Status" />
</div>
<div class="card mb-3">
    <div class="card-table">
        <x-tabler.datatable-client
            id="table-status-aktifitas-list"
            :columns="[
                ['name' => 'Status'],
                ['name' => 'TMT'],
                ['name' => 'Tgl Akhir'],
                ['name' => 'Status Aktif'],
                ['name' => 'Status Approval']
            ]"
        >
            @forelse($pegawai->historyStatAktifitas as $item)
            <tr>
                <td>
                    <span class="badge bg-yellow-lt">{{ $item->statusAktifitas->nama_status ?? ($item->statusAktifitas->stataktifitas ?? '-') }}</span>
                </td>
                <td>{{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}</td>
                <td>{{ $item->tgl_akhir ? $item->tgl_akhir->format('d F Y') : '-' }}</td>
                <td>
                    @if($pegawai->latest_riwayatstataktifitas_id == $item->riwayatstataktifitas_id)
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
