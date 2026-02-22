<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Riwayat Approval & Perubahan Data</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter table-mobile-md card-table">
            <thead>
                <tr>
                    <th>Tipe Pengajuan</th>
                    <th>Detail Perubahan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawai->allApprovals()->get() as $approval)
                <tr>
                    <td data-label="Tipe">
                        <div class="font-weight-medium">
                            {{ (new \ReflectionClass($approval->model))->getShortName() }}
                        </div>
                    </td>
                    <td data-label="Detail" class="text-muted small">
                        @php $subject = $approval->subject; @endphp
                        @if($subject)
                            @if($approval->model == \App\Models\Hr\RiwayatPendidikan::class)
                                {{ $subject->jenjang_pendidikan }} - {{ $subject->nama_pt }}
                            @elseif($approval->model == \App\Models\Hr\Keluarga::class)
                                {{ $subject->nama }} ({{ $subject->hubungan }})
                            @elseif($approval->model == \App\Models\Hr\PengembanganDiri::class)
                                {{ $subject->nama_kegiatan }}
                            @else
                                {{ $approval->keterangan }}
                            @endif
                        @else
                            Data sudah tidak ada
                        @endif
                    </td>
                    <td data-label="Status">
                        {!! getApprovalBadge($approval->status) !!}
                        @if($approval->status == 'Rejected' && $approval->keterangan)
                            <div class="text-danger small mt-1">{{ $approval->keterangan }}</div>
                        @endif
                    </td>
                    <td data-label="Tanggal">
                        {{ $approval->created_at->format('d M Y H:i') }}
                    </td>
                    <td>
                        @if($approval->status == 'Pending')
                        <div class="btn-list flex-nowrap">
                            <x-tabler.button 
                                style="success" 
                                class="btn-sm ajax-confirm" 
                                data-url="{{ route('hr.approval.approve', $approval->encrypted_riwayatapproval_id) }}" 
                                data-title="Setujui Pengajuan?"
                                icon="ti ti-check"
                                text="Setuju" />
                            
                            <x-tabler.button 
                                style="danger" 
                                class="btn-sm ajax-prompt" 
                                data-url="{{ route('hr.approval.reject', $approval->encrypted_riwayatapproval_id) }}" 
                                data-title="Tolak Pengajuan"
                                data-prompt-text="Alasan penolakan:"
                                icon="ti ti-x"
                                text="Tolak" />
                        </div>
                        @else
                            <span class="text-muted small">By {{ $approval->pejabat }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="text-muted">Tidak ada riwayat pengajuan.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
