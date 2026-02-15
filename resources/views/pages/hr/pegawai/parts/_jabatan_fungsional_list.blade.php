<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h3 class="mb-0">Riwayat Jabatan Fungsional</h3>
    <x-tabler.button 
        style="primary" 
        class="ajax-modal-btn" 
        data-url="{{ route('hr.pegawai.jabatan-fungsional.create', $pegawai->encrypted_pegawai_id) }}" 
        data-modal-title="Ubah Jabatan Fungsional"
        icon="ti ti-edit">
        Ubah Jafung
    </x-tabler.button>
</div>
<div class="card mb-3">
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped">
            <thead>
                <tr>
                    <th>Jabatan</th>
                    <th>TMT</th>
                    <th>No. SK</th>
                    <th>Status Aktif</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawai->historyJabFungsional as $item)
                <tr>
                    <td>
                        <span class="badge bg-indigo-lt">{{ $item->jabatanFungsional->jabfungsional ?? '-' }}</span>
                    </td>
                    <td>{{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}</td>
                    <td>{{ $item->no_sk_internal ?? $item->no_sk_kopertis ?? '-' }}</td>
                        @if($pegawai->latest_riwayatjabfungsional_id == $item->riwayatjabfungsional_id)
                            <span class="badge bg-success text-success-fg">Aktif Saat Ini</span>
                        @else
                            <span class="badge bg-secondary text-secondary-fg">Riwayat</span>
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
