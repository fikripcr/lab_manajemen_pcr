@extends('layouts.tabler.app')

@section('title', 'Detail Peminjaman')

@section('content')
    <x-tabler.page-header title="Detail Peminjaman" :pretitle="'#' . $kegiatan->kegiatan_id">
        <x-slot:actions>
            <x-tabler.button type="back" href="{{ route('lab.kegiatan.index') }}" />
        </x-slot:actions>
    </x-tabler.page-header>

        <div class="row row-cards">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Kegiatan</h3>
                        <div class="card-actions">
                            @php
                                $badges = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'completed' => 'info'
                                ];
                                $color = $badges[$kegiatan->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($kegiatan->status) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Nama Kegiatan</div>
                                <div class="datagrid-content">{{ $kegiatan->nama_kegiatan }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Lab</div>
                                <div class="datagrid-content">{{ $kegiatan->lab->name }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Waktu</div>
                                <div class="datagrid-content">
                                    {{ $kegiatan->tanggal->format('d M Y') }} <br>
                                    {{ $kegiatan->jam_mulai->format('H:i') }} - {{ $kegiatan->jam_selesai->format('H:i') }}
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Penyelenggara</div>
                                <div class="datagrid-content">{{ $kegiatan->penyelenggara->name }}</div>
                            </div>
                        </div>

                        <div class="mt-3"></div>
                        <label class="form-label text-muted">Deskripsi</label>
                        <div class="form-control-plaintext border p-2 rounded bg-light mb-3">
                            {{ $kegiatan->deskripsi }}
                        </div>

                        @if($kegiatan->dokumentasi_path)
                            <div class="mb-3">
                                <label class="form-label text-muted">Dokumen Pendukung</label>
                                <div>
                                    <x-tabler.button :href="asset('storage/' . $kegiatan->dokumentasi_path)" target="_blank" class="btn-outline-secondary btn-sm" icon="bx bx-file" text="Lihat Dokumen" />
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Approval History --}}
                <x-tabler.approval-history :approvals="$kegiatan->approvals" />
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Approval Action</h3>
                    </div>
                    <div class="card-body">
                        @if($kegiatan->status == 'pending')
                            <form action="{{ route('lab.kegiatan.status', encryptId($kegiatan->kegiatan_id)) }}" method="POST" class="ajax-form">
                                @csrf
                                <x-tabler.form-textarea name="catatan" label="Catatan (Optional)" rows="3" placeholder="Alasan approval/rejection..." />
                                <div class="d-flex gap-2">
                                    <x-tabler.button type="submit" name="status" value="approved" class="btn-success w-100" icon="bx bx-check" text="Setuju" />
                                    <x-tabler.button type="submit" name="status" value="rejected" class="btn-danger w-100" icon="bx bx-x" text="Tolak" />
                                </div>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-check-circle h1 text-success"></i>
                                <p class="text-muted">Status sudah diproses: <strong>{{ ucfirst($kegiatan->status) }}</strong></p>
                                @if($kegiatan->catatan_pic)
                                    <div class="alert alert-info">
                                        Catatan: {{ $kegiatan->catatan_pic }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection
