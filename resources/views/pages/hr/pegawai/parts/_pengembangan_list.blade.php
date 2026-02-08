<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Pengembangan Diri</h3>
    <a href="#" class="btn btn-primary ajax-modal-btn" data-url="{{ route('hr.pegawai.pengembangan.create', $pegawai->encrypted_pegawai_id) }}" data-modal-title="Tambah Pengembangan Diri">
        <i class="ti ti-plus me-1"></i> Tambah Kegiatan
    </a>
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
                    <td>{{ $dev->jenis_kegiatan }}</td>
                    <td>{{ $dev->nama_kegiatan }}</td>
                    <td>{{ $dev->penyelenggara }}</td>
                    <td>{{ $dev->tahun }}</td>
                    <td>
                        @if($dev->approval && $dev->approval->status == 'Pending')
                            <span class="badge bg-warning">Menunggu Approval</span>
                        @else
                            <span class="badge bg-success">Disetujui</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="#" class="btn btn-sm btn-ghost-primary ajax-modal-btn" data-url="{{ route('hr.pegawai.pengembangan.edit', [$pegawai->encrypted_pegawai_id, $dev->pengembangandiri_id]) }}" data-modal-title="Edit Pengembangan Diri">
                            <i class="ti ti-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-ghost-danger ajax-delete" data-url="{{ route('hr.pegawai.pengembangan.destroy', [$pegawai->encrypted_pegawai_id, $dev->pengembangandiri_id]) }}">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data pengembangan diri</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
