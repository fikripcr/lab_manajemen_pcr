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
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>TMT</th>
                    <th>No. SK</th>
                    <th>File SK</th>
                    <th>Status Aktif</th>
                </tr>
            </thead>
            <tbody>
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
                        @if($pegawai->latest_riwayatstatpegawai_id == $item->riwayatstatpegawai_id)
                            <span class="badge bg-success text-success-fg">Aktif Saat Ini</span>
                        @else
                            <span class="badge bg-secondary text-secondary-fg">Riwayat</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted">Belum ada data riwayat status</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
