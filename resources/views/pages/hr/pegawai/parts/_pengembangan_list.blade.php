<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Pengembangan Diri</h3>
    <x-tabler.button 
        style="primary" 
        class="ajax-modal-btn" 
        data-url="{{ route('hr.pegawai.pengembangan.create', $pegawai->encrypted_pegawai_id) }}" 
        data-modal-title="Tambah Pengembangan Diri"
        icon="ti ti-plus">
        Tambah Kegiatan
    </x-tabler.button>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped">
            <thead>
                <tr>
                    <th>Jenis Kegiatan</th>
                    <th>Nama Kegiatan</th>
                    <th>Penyelenggara</th>
                    <th>Tahun</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawai->pengembanganDiri as $dev)
                <tr>
                    <td><span class="badge bg-green-lt">{{ $dev->jenis_kegiatan }}</span></td>
                    <td class="fw-bold">{{ $dev->nama_kegiatan }}</td>
                    <td>{{ $dev->penyelenggara }}</td>
                    <td>{{ $dev->tahun }}</td>
                    <td>
                        @if($dev->approval && $dev->approval->status == 'Pending')
                            <span class="badge bg-warning text-warning-fg">Menunggu Approval</span>
                        @else
                            <span class="badge bg-success text-success-fg">Disetujui</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-list justify-content-end">
                            <x-tabler.button 
                                style="ghost-primary" 
                                class="btn-icon ajax-modal-btn" 
                                data-url="{{ route('hr.pegawai.pengembangan.edit', [$pegawai->encrypted_pegawai_id, $dev->pengembangandiri_id]) }}" 
                                data-modal-title="Edit Pengembangan Diri"
                                icon="ti ti-edit"
                                title="Edit" />
                            
                            <x-tabler.button 
                                style="ghost-danger" 
                                class="btn-icon ajax-delete" 
                                data-url="{{ route('hr.pegawai.pengembangan.destroy', [$pegawai->encrypted_pegawai_id, $dev->pengembangandiri_id]) }}"
                                icon="ti ti-trash"
                                title="Hapus" />
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data pengembangan diri</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
