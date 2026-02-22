<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Riwayat Inpassing</h3>
        <div class="card-actions">
            <x-tabler.button 
                style="primary" 
                class="ajax-modal-btn" 
                data-url="{{ route('hr.pegawai.inpassing.create', $pegawai->encrypted_pegawai_id) }}" 
                data-modal-title="Tambah Inpassing"
                icon="ti ti-plus"
                text="Tambah" />
        </div>
    </div>
    <div class="card-body">
        @forelse($pegawai->historyInpassing->sortByDesc('tmt') as $item)
        <div class="timeline">
            <div class="timeline-item {{ $loop->first ? 'timeline-item-active' : '' }}">
                <div class="timeline-badge {{ $loop->first ? 'bg-primary' : 'bg-secondary' }}">
                    <i class="ti ti-stairs-up"></i>
                </div>
                <div class="timeline-content">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title mb-1">
                                        {{ $item->golonganInpassing->golongan ?? '-' }}
                                    </h4>
                                    <div class="text-muted">{{ $item->golonganInpassing->nama_pangkat ?? '-' }}</div>
                                    <div class="text-muted small mt-1">
                                        <i class="ti ti-calendar me-1"></i>
                                        TMT: {{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}
                                    </div>
                                </div>
                                @if($pegawai->latest_riwayatinpassing_id == $item->riwayatinpassing_id)
                                <div class="col-auto">
                                    <span class="badge bg-success">Aktif Saat Ini</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-muted small">No. SK</div>
                                        <div>{{ $item->no_sk ?? '-' }}</div>
                                        <div class="text-muted small">{{ $item->tgl_sk ? $item->tgl_sk->format('d M Y') : '' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-muted small">Masa Kerja</div>
                                        <div>{{ $item->masa_kerja_tahun ?? 0 }} Tahun {{ $item->masa_kerja_bulan ?? 0 }} Bulan</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="text-muted small">Gaji Pokok</div>
                                        <div class="fw-bold">Rp {{ number_format($item->gaji_pokok ?? 0, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-muted small">File SK</div>
                                        <div>
                                            @if($item->file_sk)
                                                <x-tabler.button 
                                                    href="{{ asset($item->file_sk) }}" 
                                                    style="ghost-info" 
                                                    class="btn-sm" 
                                                    icon="ti ti-download" 
                                                    target="_blank" 
                                                    text="Unduh" />
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($item->approval)
                            <div class="mt-2">
                                {!! getApprovalBadge($item->approval->status) !!}
                            </div>
                            @endif
                            
                            <div class="mt-3">
                                <div class="btn-list">
                                    <x-tabler.button 
                                        style="ghost-primary" 
                                        class="btn-sm ajax-modal-btn" 
                                        data-url="{{ route('hr.pegawai.inpassing.edit', ['pegawai' => $pegawai->encrypted_pegawai_id, 'inpassing' => $item->encrypted_riwayatinpassing_id]) }}" 
                                        data-modal-title="Edit Inpassing"
                                        icon="ti ti-edit"
                                        text="Edit" />
                                    
                                    <x-tabler.button 
                                        style="ghost-danger" 
                                        class="btn-sm ajax-delete" 
                                        data-url="{{ route('hr.pegawai.inpassing.destroy', ['pegawai' => $pegawai->encrypted_pegawai_id, 'inpassing' => $item->encrypted_riwayatinpassing_id]) }}" 
                                        icon="ti ti-trash"
                                        text="Hapus" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <x-tabler.empty-state 
            icon="ti ti-stairs-off"
            title="Belum Ada Riwayat Inpassing"
            description="Klik tombol di atas untuk menambahkan riwayat inpassing."
        />
        @endforelse
    </div>
</div>
