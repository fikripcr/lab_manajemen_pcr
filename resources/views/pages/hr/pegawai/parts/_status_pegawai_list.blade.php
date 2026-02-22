<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Riwayat Status Pegawai</h3>
        <div class="card-actions">
            <x-tabler.button 
                style="primary" 
                class="ajax-modal-btn" 
                data-url="{{ route('hr.pegawai.status-pegawai.create', $pegawai->encrypted_pegawai_id) }}" 
                data-modal-title="Ubah Status Pegawai"
                icon="ti ti-edit"
                text="Ubah Status" />
        </div>
    </div>
    <div class="card-body">
        @forelse($pegawai->historyStatPegawai->sortByDesc('tmt') as $item)
        <div class="timeline">
            <div class="timeline-item {{ $loop->first ? 'timeline-item-active' : '' }}">
                <div class="timeline-badge {{ $loop->first ? 'bg-primary' : 'bg-secondary' }}">
                    <i class="ti ti-briefcase"></i>
                </div>
                <div class="timeline-content">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title mb-1">
                                        {{ $item->statusPegawai->nama_status ?? ($item->statusPegawai->statpegawai ?? '-') }}
                                    </h4>
                                    <div class="text-muted small">
                                        <i class="ti ti-calendar me-1"></i>
                                        TMT: {{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}
                                    </div>
                                </div>
                                @if($pegawai->latest_riwayatstatpegawai_id == $item->riwayatstatpegawai_id)
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <x-tabler.empty-state 
            icon="ti ti-briefcase-off"
            title="Belum Ada Riwayat Status"
            description="Klik tombol di atas untuk menambahkan riwayat status pegawai."
        />
        @endforelse
    </div>
</div>
