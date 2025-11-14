@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Data Lab /</span> {{ $lab->name }}</h4>
        <div class="d-flex">
            <a href="{{ route('labs.edit', $lab) }}" class="btn btn-light me-2">
                <i class='bx bx-edit me-1'></i> Edit
            </a>
            <form action="{{ route('labs.destroy', $lab) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus lab ini? Semua data terkait akan terpengaruh.')">
                    <i class='bx bx-trash me-1'></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Main Lab Info Card -->
            <div class="col-xl-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><i class='bx bx-building me-2 text-primary'></i>Detail Laboratorium</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-container">

                            <div class="info-details">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="30%"><strong>Nama:</strong></td>
                                        <td>{{ $lab->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lokasi:</strong></td>
                                        <td>{{ $lab->location }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kapasitas:</strong></td>
                                        <td><span class="badge bg-label-info">{{ $lab->capacity }} Orang</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dibuat:</strong></td>
                                        <td>{{ $lab->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Diubah:</strong></td>
                                        <td>{{ $lab->updated_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <p class="text-muted">{!! $lab->description !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lab Images Section -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class='bx bx-images me-2 text-primary'></i>Gambar Laboratorium</h5>
                        <span class="badge bg-primary bg-opacity-10 text-white">{{ $lab->getMediaByCollection('lab_images')->count() }} Gambar</span>
                    </div>
                    <div class="card-body">
                        @if ($lab->getMediaByCollection('lab_images')->count() > 0)
                            <div class="row g-3">
                                @foreach ($lab->getMediaByCollection('lab_images') as $media)
                                    @php
                                        $labMedia = $lab->labMedia()->where('media_id', $media->id)->first();
                                    @endphp
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 shadow-sm border">
                                            <img src="{{ asset('storage/' . $media->file_path) }}" class="card-img-top" alt="{{ $labMedia ? $labMedia->judul : $media->file_name }}" style="height: 200px; object-fit: cover;">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $labMedia ? $labMedia->judul : Str::limit($media->file_name, 20) }}</h6>
                                                <p class="card-text small text-muted">{{ $labMedia ? $labMedia->keterangan : Str::limit($media->file_name, 50) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">{{ round($media->file_size / 1024, 2) }} KB</small>
                                                    <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="avatar avatar-lg mx-auto mb-4 bg-light rounded">
                                    <div class="avatar-initial rounded bg-label-secondary">
                                        <i class='bx bx-images bx-lg'></i>
                                    </div>
                                </div>
                                <p class="text-muted mb-0">Belum ada gambar yang diunggah untuk laboratorium ini</p>
                                <a href="{{ route('labs.edit', $lab) }}" class="btn btn-primary mt-3">
                                    <i class='bx bx-upload me-1'></i>Tambahkan Gambar
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar with Stats -->
            <div class="col-xl-4">
                <!-- Lab Stats Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><i class='bx bx-stats me-2 text-primary'></i>Statistik Laboratorium</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <div class="d-flex align-items-center border-bottom pb-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class='bx bx-package'></i>
                                    </span>
                                </div>
                                <div class="info-title">
                                    <h5 class="mb-0">{{ $lab->inventaris->count() }}</h5>
                                    <small class="text-muted">Inventaris</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center border-bottom pb-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class='bx bx-calendar-event'></i>
                                    </span>
                                </div>
                                <div class="info-title">
                                    <h5 class="mb-0">{{ $lab->jadwals->count() }}</h5>
                                    <small class="text-muted">Jadwal</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class='bx bx-user'></i>
                                    </span>
                                </div>
                                <div class="info-title">
                                    <h5 class="mb-0">{{ $lab->pcAssignments->count() }}</h5>
                                    <small class="text-muted">Penugasan PC</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lab Inventory Section -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class='bx bx-package me-2 text-info'></i>Inventaris Laboratorium</h5>
                        <span class="badge bg-info bg-opacity-10 text-white">{{ $lab->inventaris->count() }} Barang</span>
                    </div>
                    <div class="card-body">
                        @if ($lab->inventaris->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Alat</th>
                                            <th>Kondisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lab->inventaris->take(5) as $inventaris)
                                            {{-- Show only top 5 --}}
                                            <tr>
                                                <td>{{ Str::limit($inventaris->nama_alat, 20) }}</td>
                                                <td>
                                                    @if ($inventaris->kondisi_terakhir == 'Baik')
                                                        <span class="badge bg-label-success"><i class='bx bx-check-circle me-1'></i>{{ $inventaris->kondisi_terakhir }}</span>
                                                    @elseif($inventaris->kondisi_terakhir == 'Rusak Ringan')
                                                        <span class="badge bg-label-warning"><i class='bx bx-exclamation-triangle me-1'></i>{{ $inventaris->kondisi_terakhir }}</span>
                                                    @else
                                                        <span class="badge bg-label-danger"><i class='bx bx-x-circle me-1'></i>{{ $inventaris->kondisi_terakhir }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if ($lab->inventaris->count() > 5)
                                    <a href="{{ route('inventories.index') }}?lab_id={{ encryptId($lab->lab_id) }}" class="btn btn-outline-primary w-100 mt-2">
                                        <i class='bx bx-list-ul me-1'></i>Lihat Semua Inventaris ({{ $lab->inventaris->count() }})
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class='bx bx-package bx-lg text-muted'></i>
                                <p class="text-muted mb-0 mt-2">Tidak ada inventaris dalam laboratorium ini</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lab Schedule Section -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class='bx bx-calendar me-2 text-success'></i>Jadwal Terkini</h5>
                        <span class="badge bg-success bg-opacity-10 text-white">{{ $lab->jadwals->count() }} Jadwal</span>
                    </div>
                    <div class="card-body">
                        @if ($lab->jadwals->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach ($lab->jadwals->sortByDesc('created_at')->take(3) as $jadwal)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-1">{{ $jadwal->mataKuliah->nama_mk ?? 'N/A' }}</h6>
                                                <p class="mb-0 text-muted small">
                                                    <i class='bx bx-time-five me-1'></i>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                                    <br>
                                                    <i class='bx bx-user me-1'></i>{{ $jadwal->dosen->name ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-label-primary">{{ $jadwal->hari }}</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-3">
                                <i class='bx bx-calendar bx-lg text-muted'></i>
                                <p class="text-muted mb-0 mt-2">Tidak ada jadwal dalam laboratorium ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 d-flex justify-content-end">
                <a href="{{ route('labs.index') }}" class="btn btn-secondary">
                    <i class='bx bx-arrow-back me-1'></i> Kembali ke Daftar Lab
                </a>
            </div>
        </div>
    </div>
@endsection
