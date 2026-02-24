@props([
    'approvals' => [],
    'title'     => 'Riwayat Approval',
    'emptyText' => 'Belum ada riwayat approval.',
])

<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pejabat</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Lampiran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approvals as $approval)
                    <tr>
                        <td class="text-nowrap">{{ formatTanggalWaktuIndo($approval->created_at) }}</td>
                        <td>
                            <div class="font-weight-bold">{{ $approval->pejabat }}</div>
                            @if($approval->jabatan)
                                <div class="text-muted small">{{ $approval->jabatan }}</div>
                            @endif
                        </td>
                        <td>
                            {!! getApprovalBadge($approval->status) !!}
                        </td>
                        <td>{{ $approval->catatan ?? '-' }}</td>
                        <td>
                            @if($approval->lampiran_url)
                                <a href="{{ asset('storage/' . $approval->lampiran_url) }}" target="_blank" class="btn btn-sm btn-ghost-info">
                                    <i class="ti ti-paperclip"></i> Lihat
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="ti ti-info-circle mb-2 h2 d-block"></i>
                            {{ $emptyText }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
