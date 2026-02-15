<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Riwayat Pendidikan</h3>
    <x-tabler.button 
        style="primary" 
        class="ajax-modal-btn" 
        data-url="{{ route('hr.pegawai.pendidikan.create', $pegawai->encrypted_pegawai_id) }}" 
        data-modal-title="Tambah Pendidikan"
        icon="ti ti-plus"
        text="Tambah Pendidikan" />
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped">
            <thead>
                <tr>
                    <th>Jenjang</th>
                    <th>Nama PT</th>
                    <th>Bidang Ilmu</th>
                    <th>Tahun Lulus</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawai->riwayatPendidikan as $edu)
                <tr>
                    <td><span class="badge bg-purple-lt">{{ $edu->jenjang_pendidikan }}</span></td>
                    <td>{{ $edu->nama_pt }}</td>
                    <td>{{ $edu->bidang_ilmu }}</td>
                    <td>{{ $edu->tgl_ijazah ? $edu->tgl_ijazah->format('Y') : '-' }}</td>
                    <td>
                        @if($edu->approval)
                            {!! getApprovalBadge($edu->approval->status) !!}
                        @else
                            <span class="badge bg-success text-success-fg">Active</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-list justify-content-end">
                            @if($edu->file_ijazah)
                            <x-tabler.button href="{{ asset($edu->file_ijazah) }}" style="ghost-info" class="btn-icon" icon="ti ti-download" target="_blank" title="Unduh Ijazah" />
                            @endif
                            
                            <x-tabler.button 
                                style="ghost-primary" 
                                class="btn-icon ajax-modal-btn" 
                                data-url="{{ route('hr.pegawai.pendidikan.edit', [$pegawai->encrypted_pegawai_id, $edu->riwayatpendidikan_id]) }}" 
                                data-modal-title="Edit Pendidikan"
                                icon="ti ti-edit"
                                title="Edit" />
                            
                            <x-tabler.button 
                                style="ghost-danger" 
                                class="btn-icon ajax-delete" 
                                data-url="{{ route('hr.pegawai.pendidikan.destroy', [$pegawai->encrypted_pegawai_id, $edu->riwayatpendidikan_id]) }}"
                                icon="ti ti-trash"
                                title="Hapus" />
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data pendidikan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
