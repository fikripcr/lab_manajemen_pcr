<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Keluarga</h3>
    <a href="#" class="btn btn-primary ajax-modal-btn" data-url="{{ route('hr.pegawai.keluarga.create', $pegawai->encrypted_pegawai_id) }}" data-modal-title="Tambah Anggota Keluarga">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Tambah Anggota Keluarga
    </a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Hubungan</th>
                    <th>L/P</th>
                    <th>Tgl Lahir</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawai->keluarga as $kel)
                <tr>
                    <td>{{ $kel->nama }}</td>
                    <td>{{ $kel->hubungan }}</td>
                    <td>{{ $kel->jenis_kelamin }}</td>
                    <td>{{ $kel->tgl_lahir ? \Carbon\Carbon::parse($kel->tgl_lahir)->format('d-m-Y') : '-' }}</td>
                    <td>
                        @if($kel->approval && $kel->approval->status == 'Pending')
                            <span class="badge bg-warning">Menunggu Approval</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="#" class="btn btn-sm btn-ghost-primary ajax-modal-btn" data-url="{{ route('hr.pegawai.keluarga.edit', [$pegawai->encrypted_pegawai_id, $kel->keluarga_id]) }}" data-modal-title="Edit Data Keluarga">
                            <i class="ti ti-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-ghost-danger ajax-delete" data-url="{{ route('hr.pegawai.keluarga.destroy', [$pegawai->encrypted_pegawai_id, $kel->keluarga_id]) }}">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data keluarga</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
