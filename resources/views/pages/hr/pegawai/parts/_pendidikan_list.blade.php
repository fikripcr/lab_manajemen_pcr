<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Riwayat Pendidikan</h3>
        <div class="card-actions">
            <x-tabler.button 
                style="primary" 
                class="ajax-modal-btn" 
                data-url="{{ route('hr.pegawai.pendidikan.create', $pegawai->encrypted_pegawai_id) }}" 
                data-modal-title="Tambah Pendidikan"
                icon="ti ti-plus"
                text="Tambah" />
        </div>
    </div>
    <div class="card-body">
        @forelse($pegawai->riwayatPendidikan as $edu)

        <div class="card card-sm mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="avatar avatar-md" style="background-color: var(--tblr-purple-lt);">
                            <i class="ti ti-school fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <h4 class="card-title mb-1">{{ $edu->nama_pt }}</h4>
                        <div class="text-muted">
                            <span class="badge bg-purple-lt me-2">{{ $edu->jenjang_pendidikan }}</span>
                            {{ $edu->bidang_ilmu }}
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="ti ti-calendar me-1"></i>
                            Lulus: {{ $edu->tgl_ijazah ? $edu->tgl_ijazah->format('Y') : '-' }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="btn-list">
                            @if($edu->file_ijazah)
                            <x-tabler.button 
                                href="{{ asset($edu->file_ijazah) }}" 
                                style="ghost-info" 
                                class="btn-icon" 
                                icon="ti ti-download" 
                                target="_blank" 
                                title="Unduh Ijazah" />
                            @endif
                            
                            <x-tabler.button 
                                style="ghost-primary" 
                                class="btn-icon ajax-modal-btn" 
                                data-url="{{ route('hr.pegawai.pendidikan.edit', [$pegawai->encrypted_pegawai_id, $edu->encrypted_riwayatpendidikan_id]) }}" 
                                data-modal-title="Edit Pendidikan"
                                icon="ti ti-edit"
                                title="Edit" />
                            
                            <x-tabler.button 
                                style="ghost-danger" 
                                class="btn-icon ajax-delete" 
                                data-url="{{ route('hr.pegawai.pendidikan.destroy', [$pegawai->encrypted_pegawai_id, $edu->encrypted_riwayatpendidikan_id]) }}"
                                icon="ti ti-trash"
                                title="Hapus" />
                        </div>
                    </div>
                </div>
                
                @if($edu->approval)
                <div class="mt-2">
                    {!! getApprovalBadge($edu->approval->status) !!}
                </div>
                @endif
            </div>
        </div>
        @empty
        <x-tabler.empty-state 
            icon="ti ti-school-off"
            title="Belum Ada Riwayat Pendidikan"
            description="Klik tombol di atas untuk menambahkan riwayat pendidikan."
        />
        @endforelse
    </div>
</div>
