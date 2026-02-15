<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data Keluarga</h3>
    <x-tabler.button 
        style="primary" 
        class="ajax-modal-btn" 
        data-url="{{ route('hr.pegawai.keluarga.create', $pegawai->encrypted_pegawai_id) }}" 
        data-modal-title="Tambah Anggota Keluarga"
        icon="ti ti-plus"
        text="Tambah Anggota Keluarga" />
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
                    <td class="fw-bold">{{ $kel->nama }}</td>
                    <td>{{ $kel->hubungan }}</td>
                    <td>{{ $kel->jenis_kelamin }}</td>
                    <td>{{ $kel->tgl_lahir ? \Carbon\Carbon::parse($kel->tgl_lahir)->format('d F Y') : '-' }}</td>
                    <td>
                        @if($kel->approval && $kel->approval->status == 'Pending')
                            <span class="badge bg-warning text-warning-fg">Menunggu Approval</span>
                        @else
                            <span class="badge bg-success text-success-fg">Active</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-list justify-content-end">
                            <x-tabler.button 
                                style="ghost-primary" 
                                class="btn-icon ajax-modal-btn" 
                                data-url="{{ route('hr.pegawai.keluarga.edit', [$pegawai->encrypted_pegawai_id, $kel->keluarga_id]) }}" 
                                data-modal-title="Edit Data Keluarga"
                                icon="ti ti-edit"
                                title="Edit" />
                            
                            <x-tabler.button 
                                style="ghost-danger" 
                                class="btn-icon ajax-delete" 
                                data-url="{{ route('hr.pegawai.keluarga.destroy', [$pegawai->encrypted_pegawai_id, $kel->keluarga_id]) }}"
                                icon="ti ti-trash"
                                title="Hapus" />
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data keluarga</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
