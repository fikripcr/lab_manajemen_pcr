<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Riwayat Pendidikan</h3>
    <a href="#" class="btn btn-primary ajax-modal-btn" data-url="{{ route('hr.pegawai.pendidikan.create', $pegawai->encrypted_pegawai_id) }}" data-modal-title="Tambah Pendidikan">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Tambah Pendidikan
    </a>
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawai->riwayatPendidikan as $edu)
                <tr>
                    <td>{{ $edu->jenjang_pendidikan }}</td>
                    <td>{{ $edu->nama_pt }}</td>
                    <td>{{ $edu->bidang_ilmu }}</td>
                    <td>{{ $edu->tgl_ijazah ? $edu->tgl_ijazah->format('Y') : '-' }}</td>
                    <td>
                        @if($edu->approval)
                            {!! getApprovalBadge($edu->approval->status) !!}
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    <td>
                    <td>
                        <div class="btn-list flex-nowrap">
                            @if($edu->file_ijazah)
                            <a href="#" class="btn btn-ghost-secondary btn-sm btn-icon" aria-label="Download">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg>
                            </a>
                            @endif
                            <a href="#" class="btn btn-sm btn-ghost-primary ajax-modal-btn" data-url="{{ route('hr.pegawai.pendidikan.edit', [$pegawai->encrypted_pegawai_id, $edu->riwayatpendidikan_id]) }}" data-modal-title="Edit Pendidikan">
                                <i class="ti ti-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-ghost-danger ajax-delete" data-url="{{ route('hr.pegawai.pendidikan.destroy', [$pegawai->encrypted_pegawai_id, $edu->riwayatpendidikan_id]) }}">
                                <i class="ti ti-trash"></i>
                            </button>
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
