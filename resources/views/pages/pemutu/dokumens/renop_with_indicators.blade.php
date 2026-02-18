@if(request()->ajax() || request()->has('ajax'))
    <div class="modal-header">
        <h5 class="modal-title">Indikator {{ ucfirst($dokumen->jenis) }}: {{ $dokumen->judul }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="alert alert-info py-2 mb-3">
            <div class="d-flex">
                <i class="ti ti-info-circle me-2 mt-1"></i>
                <div class="small">Akumulasi indikator dari seluruh sub-dokumen (Total: {{ $indicators->count() }}).</div>
            </div>
        </div>

        <div class="table-responsive border rounded">
            <table class="table table-vcenter table-sm card-table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Indikator</th>
                        <th>Target</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($indicators as $indicator)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $indicator->no_indikator }}</strong><br>
                                <small class="text-muted">{{ \Str::limit($indicator->indikator, 60) }}</small>
                            </td>
                            <td>{{ $indicator->target ?? '-' }}</td>
                            <td>
                                <x-tabler.button href="{{ route('pemutu.indikators.show', $indicator->indikator_id) }}" 
                                   class="btn-sm btn-icon btn-ghost-info" 
                                   title="Detail" icon="ti ti-eye" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada indikator.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tutup</button>
        <x-tabler.button type="a" :href="route('pemutu.dokumens.show', $dokumen->dok_id)" class="btn-primary" text="Detail Dokumen" />
    </div>
@else
    @extends('layouts.admin.app')

    @section('header')
    <x-tabler.page-header title="Total Indikator {{ ucfirst($dokumen->jenis) }} : ({{ $indicators->count() }})" pretitle="Dokumen SPMI">
        <x-slot:actions>
            <x-tabler.button href="javascript:history.back()" class="btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" />
        </x-slot:actions>
    </x-tabler.page-header>
    @endsection

    @section('content')
    <div class="page-body">
        <div class="container-xl">
            <ol class="breadcrumb" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="{{ route('pemutu.dokumens.index', ['tabs' => 'kebijakan']) }}">Dokumen SPMI</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pemutu.dokumens.show', $dokumen->dok_id) }}">{{ $dokumen->judul }}</a></li>
                <li class="nav-item active" aria-current="page">Indikator</li>
            </ol>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-uppercase">Detail Indikator {{ ucfirst($dokumen->jenis) }}</h3>
                    <div class="card-actions">
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h4 class="alert-title">Informasi</h4>
                        <div>Halaman ini menyajikan akumulasi indikator dari seluruh sub-dokumen atau poin-poin yang terdapat dalam <strong>{{ $dokumen->judul }}</strong>.</div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-vcenter datatable">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Indikator</th>
                                    <th>Target</th>
                                    <th>Tipe</th>
                                    <th>Periode</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($indicators as $indicator)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $indicator->no_indikator }}</strong><br>
                                        <small class="text-muted">{{ strlen($indicator->indikator) > 100 ? substr($indicator->indikator, 0, 100) . '...' : $indicator->indikator }}</small>
                                    </td>
                                    <td>{{ $indicator->target ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-blue-lt">{{ $indicator->jenis_indikator ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($indicator->periode_mulai && $indicator->periode_selesai)
                                            {{ \Carbon\Carbon::parse($indicator->periode_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($indicator->periode_selesai)->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <x-tabler.button href="{{ route('pemutu.indikators.show', $indicator->indikator_id) }}" 
                                               class="btn-sm btn-icon btn-ghost-info" 
                                               title="Detail" icon="ti ti-eye" />
                                            <x-tabler.button href="{{ route('pemutu.indikators.edit', $indicator->indikator_id) }}" 
                                               class="btn-sm btn-icon btn-ghost-primary" 
                                               title="Edit" icon="ti ti-pencil" />
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="empty">
                                            <div class="empty-img">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg>
                                            </div>
                                            <p class="empty-title">Tidak ada indikator ditemukan</p>
                                            <p class="empty-subtitle text-muted">
                                                Belum ada indikator yang terkait dengan dokumen ini.
                                            </p>
                                            <div class="empty-action">
                                                <p class="text-muted small">Silahkan klik pada "Kegiatan" di panel sebelah kiri untuk menambah indikator baru.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
@endif