{{-- views/pages/hr/approval/show.blade.php --}}
{{-- Loaded dynamically via AJAX modal --}}

@php
    $isNewAddition = $before === null;
    $statusColors  = [
        'Pending'    => 'warning',
        'Approved'   => 'success',
        'Rejected'   => 'danger',
        'Tangguhkan' => 'info',
    ];
    $statusColor = $statusColors[$approval->status] ?? 'secondary';

    $changedDiffs   = collect($diffs)->where('changed', true)->values();
    $unchangedDiffs = collect($diffs)->where('changed', false)->values();
@endphp

{{-- Pegawai Profile Strip --}}
<div class="border-bottom pb-3 mb-3">
    <div class="d-flex align-items-center gap-3">
        @if($pegawai && $pegawai->photo)
            <img src="{{ asset('storage/' . $pegawai->photo) }}" class="avatar avatar-md rounded-circle" alt="foto">
        @else
            <span class="avatar avatar-md bg-primary-lt rounded-circle text-primary fw-bold">
                {{ strtoupper(substr($pegawai?->nama ?? '?', 0, 1)) }}
            </span>
        @endif
        <div>
            <div class="fw-bold fs-4">{{ $pegawai?->nama ?? '-' }}</div>
            <div class="text-muted small">{{ $pegawai?->latestDataDiri?->nip ?? '-' }}
                &bull; {{ hrModelLabel($approval->model) }}
            </div>
        </div>
        <div class="ms-auto">
            <span class="badge bg-{{ $statusColor }}-lt text-{{ $statusColor }} fs-5 px-3 py-2">
                {{ $approval->status }}
            </span>
        </div>
    </div>
    <div class="row mt-2 text-muted small">
        <div class="col-auto">
            <i class="ti ti-calendar me-1"></i>Diajukan: {{ $approval->created_at?->format('d M Y H:i') ?? '-' }}
        </div>
        @if($approval->pejabat)
        <div class="col-auto">
            <i class="ti ti-user-check me-1"></i>Diproses oleh: {{ $approval->pejabat }}
        </div>
        @endif
        @if($approval->keterangan)
        <div class="col-auto">
            <i class="ti ti-message me-1"></i>Catatan: {{ $approval->keterangan }}
        </div>
        @endif
    </div>
</div>

@if($isNewAddition)
<div class="alert alert-info py-2 mb-3">
    <i class="ti ti-plus-circle me-1"></i>
    <strong>Data Baru</strong> — Ini adalah penambahan data baru, belum ada data sebelumnya.
</div>
@else
<div class="alert alert-warning py-2 mb-3">
    <i class="ti ti-replace me-1"></i>
    <strong>Perubahan Data</strong> —
    <span class="badge bg-danger me-1">{{ $changedDiffs->count() }}</span> field berubah,
    <span class="badge bg-secondary">{{ $unchangedDiffs->count() }}</span> tidak berubah.
</div>
@endif

@if(count($diffs) > 0)
<div class="table-responsive">
    <table class="table table-sm table-vcenter card-table mb-0">
        <thead class="bg-light">
            <tr>
                <th style="width: 28%">Field</th>
                <th style="width: 36%">
                    @if($isNewAddition)
                        <span class="text-muted fst-italic">— (Data Baru)</span>
                    @else
                        <i class="ti ti-history me-1 text-secondary"></i>Data Lama
                    @endif
                </th>
                <th style="width: 36%">
                    <i class="ti ti-arrow-right me-1 text-success"></i>Data Baru
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($diffs as $diff)
            @php
                $rowClass = '';
                if (!$isNewAddition && $diff['changed']) {
                    $rowClass = 'table-warning';
                } elseif ($isNewAddition) {
                    $rowClass = 'table-success';
                }
            @endphp
            <tr class="{{ $rowClass }}">
                <td>
                    <span class="fw-medium text-dark">{{ $diff['label'] }}</span>
                    @if(!$isNewAddition && $diff['changed'])
                        <span class="badge bg-danger-lt text-danger ms-1" style="font-size:10px">berubah</span>
                    @endif
                </td>
                <td class="text-secondary">
                    @if($isNewAddition)
                        <span class="text-muted fst-italic">—</span>
                    @elseif($diff['old'] !== null && $diff['old'] !== '')
                        <span @if($diff['changed']) class="text-decoration-line-through text-danger" @endif>
                            {{ $diff['old'] }}
                        </span>
                    @else
                        <span class="text-muted fst-italic">kosong</span>
                    @endif
                </td>
                <td>
                    @if($diff['new'] !== null && $diff['new'] !== '')
                        <span @if(!$isNewAddition && $diff['changed']) class="fw-bold text-success" @endif>
                            {{ $diff['new'] }}
                        </span>
                    @else
                        <span class="text-muted fst-italic">kosong</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center text-muted py-4">
    <i class="ti ti-info-circle fs-1 d-block mb-2"></i>
    Tidak ada data yang dapat ditampilkan.
</div>
@endif

{{-- Action Buttons — only if still Pending --}}
@if($approval->status === 'Pending')
<div class="border-top mt-3 pt-3 d-flex justify-content-end gap-2">
    <button type="button" class="btn btn-outline-secondary btn-process"
        data-url="{{ route('hr.approval.process', $approval->riwayatapproval_id) }}"
        data-status="Tangguhkan" data-need-reason="0">
        <i class="ti ti-clock-pause me-1"></i>Tangguhkan
    </button>
    <button type="button" class="btn btn-danger btn-process"
        data-url="{{ route('hr.approval.process', $approval->riwayatapproval_id) }}"
        data-status="Rejected" data-need-reason="1">
        <i class="ti ti-x me-1"></i>Tolak
    </button>
    <button type="button" class="btn btn-success btn-process"
        data-url="{{ route('hr.approval.process', $approval->riwayatapproval_id) }}"
        data-status="Approved" data-need-reason="0">
        <i class="ti ti-check me-1"></i>Setujui
    </button>
</div>
@else
<div class="border-top mt-3 pt-3 text-center small">
    <span class="badge bg-{{ $statusColor }}-lt text-{{ $statusColor }} px-3 py-2 fs-6">
        {{ $approval->status }}
    </span>
    @if($approval->pejabat)
    <div class="text-muted mt-1">oleh <strong>{{ $approval->pejabat }}</strong>
        @if($approval->keterangan) — {{ $approval->keterangan }}@endif
    </div>
    @endif
</div>
@endif
