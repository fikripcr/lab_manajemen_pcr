<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h3>Riwayat Status Aktifitas</h3>
        <a href="#" class="btn btn-primary btn-sm ajax-modal-btn" data-url="{{ route('hr.pegawai.status-aktifitas.create', $pegawai->encrypted_pegawai_id) }}" data-modal-title="Ubah Status Aktifitas">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Ubah Status Aktifitas
    </a>
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
