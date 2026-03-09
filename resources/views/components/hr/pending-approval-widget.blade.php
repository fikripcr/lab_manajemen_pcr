<x-tabler.card>
    <x-tabler.card-header title="Persetujuan Pending" icon="ti ti-clock">
        <x-slot:actions>
            <x-tabler.button href="{{ route('hr.approval.index') }}" class="btn-primary btn-sm" text="Lihat Semua" />
        </x-slot:actions>
    </x-tabler.card-header>
    <x-tabler.card-body>
        @if($pendingCount > 0)
            <div class="alert alert-warning mb-3">
                <i class="ti ti-alert-triangle me-2"></i>
                <strong>{{ $pendingCount }}</strong> pengajuan menunggu persetujuan
            </div>

            @if($recentApprovals->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentApprovals as $approval)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">
                                    {{ $approval->pegawai->nama ?? 'Tidak diketahui' }}
                                </div>
                                <div class="text-muted small">
                                    {{ class_basename($approval->model) }}
                                    <span class="text-muted">•</span>
                                    {{ $approval->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="ms-3">
                                <x-tabler.button href="{{ route('hr.approval.index') }}" class="btn-outline-primary btn-sm" text="Review" />
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <i class="ti ti-check text-success" style="font-size: 3rem;"></i>
                <div class="mt-3">
                    <div class="fw-semibold">Tidak Ada Pengajuan</div>
                    <div class="text-muted">Semua pengajuan telah diproses</div>
                </div>
            </div>
        @endif
    </x-tabler.card-body>
</x-tabler.card>
