<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Riwayat Status Aktifitas</h3>
        <div class="card-actions">
            <x-tabler.button 
                style="primary" 
                class="ajax-modal-btn" 
                data-url="{{ route('hr.pegawai.status-aktifitas.create', $pegawai->encrypted_pegawai_id) }}" 
                data-modal-title="Ubah Status Aktifitas"
                icon="ti ti-edit"
                text="Ubah Status" />
        </div>
    </div>
    <div class="card-body">
        @forelse($pegawai->historyStatAktifitas->sortByDesc('tmt') as $item)
        <div class="timeline">
            <div class="timeline-item {{ $loop->first ? 'timeline-item-active' : '' }}">
                <div class="timeline-badge {{ $loop->first ? 'bg-primary' : 'bg-secondary' }}">
                    <i class="ti ti-activity"></i>
                </div>
                <div class="timeline-content">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title mb-1">
                                        {{ $item->statusAktifitas->nama_status ?? ($item->statusAktifitas->stataktifitas ?? '-') }}
                                    </h4>
                                    <div class="text-muted small">
                                        <i class="ti ti-calendar me-1"></i>
                                        TMT: {{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}
                                    </div>
                                </div>
                                @if($pegawai->latest_riwayatstataktifitas_id == $item->riwayatstataktifitas_id)
                                <div class="col-auto">
                                    <span class="badge bg-success">Aktif Saat Ini</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-muted small">Tanggal Mulai</div>
                                        <div>{{ $item->tmt ? $item->tmt->format('d F Y') : '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-muted small">Tanggal Akhir</div>
                                        <div>{{ $item->tgl_akhir ? $item->tgl_akhir->format('d F Y') : '-' }}</div>
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
            icon="ti ti-activity-off"
            title="Belum Ada Riwayat Status Aktifitas"
            description="Klik tombol di atas untuk menambahkan riwayat status aktifitas."
        />
        @endforelse
    </div>
</div>
