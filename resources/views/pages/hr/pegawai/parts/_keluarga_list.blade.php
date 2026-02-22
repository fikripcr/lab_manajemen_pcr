<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Data Keluarga</h3>
        <div class="card-actions">
            <x-tabler.button 
                style="primary" 
                class="ajax-modal-btn" 
                data-url="{{ route('hr.pegawai.keluarga.create', $pegawai->encrypted_pegawai_id) }}" 
                data-modal-title="Tambah Anggota Keluarga"
                icon="ti ti-plus"
                text="Tambah" />
        </div>
    </div>
    <div class="card-body">
        @forelse($pegawai->keluarga as $kel)

        <div class="row row-cards">
            <div class="col-md-6 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-md me-3" 
                                  style="background-image: url({{ asset('static/avatars/00' . ($kel->jenis_kelamin == 'L' ? '0m' : '0f') . '.jpg') }})">
                            </span>
                            <div class="flex-fill">
                                <h4 class="card-title mb-0">{{ $kel->nama }}</h4>
                                <div class="text-muted small">{{ $kel->hubungan }}</div>
                            </div>
                        </div>
                        
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Jenis Kelamin</div>
                                <div class="datagrid-content">{{ $kel->jenis_kelamin }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Tanggal Lahir</div>
                                <div class="datagrid-content">
                                    {{ $kel->tgl_lahir ? \Carbon\Carbon::parse($kel->tgl_lahir)->format('d F Y') : '-' }}
                                </div>
                            </div>
                        </div>
                        
                        @if($kel->approval)
                        <div class="mt-2">
                            {!! getApprovalBadge($kel->approval->status) !!}
                        </div>
                        @endif
                        
                        <div class="mt-3">
                            <div class="btn-list">
                                <x-tabler.button 
                                    style="ghost-primary" 
                                    class="btn-sm ajax-modal-btn" 
                                    data-url="{{ route('hr.pegawai.keluarga.edit', [$pegawai->encrypted_pegawai_id, $kel->encrypted_keluarga_id]) }}" 
                                    data-modal-title="Edit Data Keluarga"
                                    icon="ti ti-edit"
                                    text="Edit" />
                                
                                <x-tabler.button 
                                    style="ghost-danger" 
                                    class="btn-sm ajax-delete" 
                                    data-url="{{ route('hr.pegawai.keluarga.destroy', [$pegawai->encrypted_pegawai_id, $kel->encrypted_keluarga_id]) }}"
                                    icon="ti ti-trash"
                                    text="Hapus" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <x-tabler.empty-state 
            icon="ti ti-users-off"
            title="Belum Ada Data Keluarga"
            description="Klik tombol di atas untuk menambahkan data keluarga."
        />
        @endforelse
    </div>
</div>
