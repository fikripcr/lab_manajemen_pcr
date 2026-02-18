@extends('layouts.tabler.app')

@section('title', 'Laporan Pelanggaran Ujian')

@section('content')
<x-tabler.page-header title="Laporan Pelanggaran" pretitle="CBT Monitoring">
    <x-slot:actions>
        <x-tabler.button class="btn-outline-secondary" onclick="location.reload()" icon="ti ti-refresh" text="Refresh" />
    </x-slot:actions>
</x-tabler.page-header>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pelanggaran Terbaru</h3>
            </div>
            <x-tabler.datatable-client
                id="table-violations"
                :columns="[
                    ['name' => 'No'],
                    ['name' => 'Peserta'],
                    ['name' => 'Ujian / Sesi'],
                    ['name' => 'Jenis Pelanggaran'],
                    ['name' => 'Keterangan'],
                    ['name' => 'Waktu Kejadian']
                ]"
            >
                @forelse($violations as $v)
                    <tr>
                        <td>{{ ($violations->currentPage() - 1) * $violations->perPage() + $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="font-weight-bold">{{ $v->riwayatUjianSiswa->user->name }}</div>
                            </div>
                            <div class="small text-muted">{{ $v->riwayatUjianSiswa->user->username }}</div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px">
                                {{ $v->riwayatUjianSiswa->jadwal->nama_kegiatan }}
                            </div>
                            <small class="text-muted">{{ $v->riwayatUjianSiswa->jadwal->waktu_mulai->format('d M Y') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-red-lt px-2 py-1">
                                <i class="ti ti-alert-circle me-1"></i> {{ str_replace('_', ' ', $v->jenis_pelanggaran) }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $v->keterangan ?: '-' }}</small>
                        </td>
                        <td>
                            <div>{{ $v->waktu_kejadian->format('H:i:s') }}</div>
                            <small class="text-muted">{{ $v->waktu_kejadian->diffForHumans() }}</small>
                        </td>
                    </tr>
                @empty
                    {{-- x-tabler.datatable-client handles empty state, or you can supply slot --}}
                @endforelse
            </x-tabler.datatable-client>

            @if($violations->isEmpty())
                <div class="text-center py-5 text-muted">
                    <div class="mb-2"><i class="ti ti-shield-check ti-lg text-success"></i></div>
                    Belum ada pelanggaran yang tercatat.
                </div>
            @endif
            @if($violations->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $violations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
