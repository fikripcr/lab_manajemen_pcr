<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Riwayat Pendidikan</h3>
    <x-tabler.button 
        icon="ti ti-plus" 
        modal-url="{{ route('hr.pegawai.pendidikan.create', $pegawai->encrypted_pegawai_id) }}" 
        modal-title="Tambah Pendidikan">
        Tambah Pendidikan
    </x-tabler.button>
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
                            <x-tabler.button 
                                class="btn-sm btn-ghost-primary" 
                                icon="ti ti-edit" 
                                modal-url="{{ route('hr.pegawai.pendidikan.edit', [$pegawai->encrypted_pegawai_id, $edu->riwayatpendidikan_id]) }}" 
                                modal-title="Edit Pendidikan"
                            />
                            <x-tabler.button 
                                type="button" 
                                class="btn-sm btn-ghost-danger ajax-delete" 
                                icon="ti ti-trash" 
                                data-url="{{ route('hr.pegawai.pendidikan.destroy', [$pegawai->encrypted_pegawai_id, $edu->riwayatpendidikan_id]) }}"
                            />
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
