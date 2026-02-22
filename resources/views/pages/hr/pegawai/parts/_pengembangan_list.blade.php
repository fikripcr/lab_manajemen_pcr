<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Pengembangan Diri</h3>
        <div class="card-actions">
            <x-tabler.button 
                style="primary" 
                class="ajax-modal-btn" 
                data-url="{{ route('hr.pegawai.pengembangan.create', $pegawai->encrypted_pegawai_id) }}" 
                data-modal-title="Tambah Pengembangan Diri"
                icon="ti ti-plus"
                text="Tambah" />
        </div>
    </div>
    <div class="card-body">
        @forelse($pegawai->pengembanganDiri as $dev)
        <div class="card card-sm mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="avatar avatar-md" style="background-color: var(--tblr-green-lt);">
                            <i class="ti ti-certificate fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <h4 class="card-title mb-1">{{ $dev->nama_kegiatan }}</h4>
                        <div class="text-muted">
                            <span class="badge bg-green-lt me-2">{{ $dev->jenis_kegiatan }}</span>
                            {{ $dev->penyelenggara }}
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="ti ti-calendar me-1"></i>
                            Tahun: {{ $dev->tahun }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="btn-list">
                            @if($dev->file_sertifikat)
                            <x-tabler.button 
                                href="{{ asset($dev->file_sertifikat) }}" 
                                style="ghost-info" 
                                class="btn-icon" 
                                icon="ti ti-download" 
                                target="_blank" 
                                title="Unduh Sertifikat" />
                            @endif
                            
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
                    </div>
                </div>
                
                @if($dev->approval)
                <div class="mt-2">
                    {!! getApprovalBadge($dev->approval->status) !!}
                </div>
                @endif
            </div>
        </div>
        @empty
        <x-tabler.empty-state 
            icon="ti ti-certificate-off"
            title="Belum Ada Pengembangan Diri"
            description="Klik tombol di atas untuk menambahkan kegiatan pengembangan diri."
        />
        @endforelse
    </div>
</div>
