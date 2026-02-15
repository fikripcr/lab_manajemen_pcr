<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Keluarga</h3>
    <x-tabler.button 
        icon="ti ti-plus" 
        modal-url="{{ route('hr.pegawai.keluarga.create', $pegawai->encrypted_pegawai_id) }}" 
        modal-title="Tambah Anggota Keluarga">
        Tambah Anggota Keluarga
    </x-tabler.button>
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
                        <x-tabler.button 
                            class="btn-sm btn-ghost-primary" 
                            icon="ti ti-edit" 
                            modal-url="{{ route('hr.pegawai.keluarga.edit', [$pegawai->encrypted_pegawai_id, $kel->keluarga_id]) }}" 
                            modal-title="Edit Data Keluarga"
                        />
                        <x-tabler.button 
                            type="button" 
                            class="btn-sm btn-ghost-danger ajax-delete" 
                            icon="ti ti-trash" 
                            data-url="{{ route('hr.pegawai.keluarga.destroy', [$pegawai->encrypted_pegawai_id, $kel->keluarga_id]) }}"
                        />
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data keluarga</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
