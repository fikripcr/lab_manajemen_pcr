<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h3>Riwayat Jabatan Fungsional</h3>
    <a href="#" class="btn btn-primary btn-sm ajax-modal-btn" data-url="{{ route('hr.pegawai.jabatan-fungsional.create', $pegawai->encrypted_pegawai_id) }}" data-modal-title="Ubah Jabatan Fungsional">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Ubah Jafung
    </a>
</div>
<div class="card mb-3">
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped">
            <thead>
                <tr>
                    <th>Jabatan</th>
                    <th>TMT</th>
                    <th>No. SK</th>
                    <th>Status Approval</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawai->historyJabFungsional as $item)
                <tr>
                    <td>{{ $item->jabatanFungsional->jabfungsional ?? '-' }}</td>
                    <td>{{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}</td>
                    <td>{{ $item->no_sk_internal ?? $item->no_sk_kopertis ?? '-' }}</td>
                    <td>
                        @if($pegawai->latest_riwayatjabfungsional_id == $item->riwayatjabfungsional_id)
                            <span class="badge bg-success">Aktif Saat Ini</span>
                        @else
                            <span class="badge bg-secondary">Riwayat</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted">Belum ada data riwayat jabatan fungsional</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
