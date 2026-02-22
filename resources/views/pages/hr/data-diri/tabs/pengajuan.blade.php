@extends('layouts.tabler.app')

@section('header')
    @include('components.hr.profile-header')
@endsection

@section('content')
<div class="card">
    @include('pages.hr.data-diri.global-tab-nav')
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Pengajuan</th>
                        <th>Status</th>
                        <th>Pejabat Approval</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvals as $approval)
                    <tr>
                        <td class="text-secondary">
                            {{ $approval->created_at->format('d M Y H:i') }}
                        </td>
                        <td>
                            <div class="fw-bold">
                                @php
                                    $modelClass = $approval->model;
                                    $shortName = $modelClass ? (new \ReflectionClass($modelClass))->getShortName() : 'N/A';
                                    $shortName = str_replace('Riwayat', '', $shortName);
                                @endphp
                                {{ $shortName }}
                            </div>
                        </td>
                        <td>
                            {!! getApprovalBadge($approval->status) !!}
                        </td>
                        <td class="text-secondary">
                            {{ $approval->pejabat ?? '-' }}
                        </td>
                        <td class="text-secondary small">
                            {{ $approval->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada riwayat pengajuan perubahan data.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($approvals->hasPages())
        <div class="card-footer d-flex align-items-center">
            {{ $approvals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
