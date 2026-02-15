<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h3>Riwayat Status Aktifitas</h3>
    <x-tabler.button 
        class="btn-sm" 
        icon="ti ti-edit" 
        modal-url="{{ route('hr.pegawai.status-aktifitas.create', $pegawai->encrypted_pegawai_id) }}" 
        modal-title="Ubah Status Aktifitas">
        Ubah Status Aktifitas
    </x-tabler.button>
</div>
<div class="card mb-3">
        <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>TMT</th>
                    <th>Tgl Akhir</th>
                    <th>Status Approval</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawai->historyStatAktifitas as $item)
                <tr>
                    <td>{{ $item->statusAktifitas->nama_status ?? '-' }}</td>
                    <td>{{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}</td>
                    <td>{{ $item->tgl_akhir ? $item->tgl_akhir->format('d F Y') : '-' }}</td>
                    <td>
                        @if($pegawai->latest_riwayatstataktifitas_id == $item->riwayatstataktifitas_id)
                            <span class="badge bg-success">Aktif Saat Ini</span>
                        @else
                            <span class="badge bg-secondary">Riwayat</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted">Belum ada data riwayat status aktifitas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
