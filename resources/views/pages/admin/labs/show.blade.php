@section('header')
    <x-sys.page-header :title="$lab->name" pretitle="Laboratorium">
        <x-slot:actions>
            <x-sys.button type="edit" :href="route('labs.edit', $lab->encrypted_lab_id)" />
            <x-sys.button type="back" :href="route('labs.index')" />
        </x-slot:actions>
    </x-sys.page-header>
@endsection

@section('content')
    <div class="row row-cards">
        <!-- Sidebar stats -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Laboratorium</h3>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Inventaris</div>
                            <div class="datagrid-content">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-xs me-2 rounded bg-info-lt">
                                        <i class="ti ti-package fs-2"></i>
                                    </span>
                                    <span class="fw-bold">{{ $lab->labInventaris->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Jadwal</div>
                            <div class="datagrid-content">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-xs me-2 rounded bg-success-lt">
                                        <i class="ti ti-calendar fs-2"></i>
                                    </span>
                                    <span class="fw-bold">{{ $lab->jadwals->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Tim</div>
                            <div class="datagrid-content">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-xs me-2 rounded bg-warning-lt">
                                        <i class="ti ti-users fs-2"></i>
                                    </span>
                                    <span class="fw-bold">{{ $lab->labTeams->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Jadwal Terkini</h3>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($lab->jadwals->sortByDesc('created_at')->take(3) as $jadwal)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    <div class="text-reset d-block fw-bold">{{ $jadwal->mataKuliah->nama_mk ?? 'N/A' }}</div>
                                    <div class="d-block text-muted text-truncate mt-n1">
                                        {{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card-body text-center py-4 text-muted small">
                            Tidak ada jadwal aktif.
                        </div>
                    @endforelse
                </div>
                @if($lab->jadwals->count() > 0)
                <div class="card-footer text-center">
                    <a href="{{ route('jadwal.index') }}" class="small">Lihat Semua Jadwal</a>
                </div>
                @endif
            </div>
        </div>

        <!-- Main content -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Laboratorium</h3>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Nama Lab</div>
                            <div class="datagrid-content fw-bold">{{ $lab->name }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Lokasi</div>
                            <div class="datagrid-content">{{ $lab->location }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Kapasitas</div>
                            <div class="datagrid-content">{{ $lab->capacity }} Orang</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="datagrid-title">Deskripsi</div>
                        <div class="datagrid-content prose max-w-none mt-2">
                            {!! $lab->description ?: '<span class="text-muted italic">Tidak ada deskripsi.</span>' !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Gambar Laboratorium</h3>
                    <a href="{{ route('labs.edit', $lab->encrypted_lab_id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus me-1"></i> Edit Gambar
                    </a>
                </div>
                <div class="card-body">
                    @if ($lab->getMedia('lab_images')->count() > 0)
                        <div class="row g-2">
                            @foreach ($lab->getMedia('lab_images') as $media)
                                <div class="col-4 col-md-3">
                                    <a href="{{ $media->getUrl() }}" target="_blank" class="d-block shadow-none">
                                        <img src="{{ $media->getUrl() }}" class="rounded img-fluid" alt="{{ $media->name }}" style="height: 120px; width: 100%; object-fit: cover;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted italic">
                            Belum ada gambar yang diunggah.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Tim Laboratorium</h3>
                    <a href="{{ route('labs.teams.index', $lab->encrypted_lab_id) }}" class="btn btn-sm btn-outline-primary">
                        Kelola Tim
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-nowrap">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lab->getActiveTeamMembers() as $teamMember)
                                <tr>
                                    <td>{{ $teamMember->user->name }}</td>
                                    <td class="text-muted">{{ $teamMember->user->email }}</td>
                                    <td>{{ $teamMember->jabatan ?: '-' }}</td>
                                    <td>
                                        <span class="badge bg-success-lt">Aktif</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada anggota tim aktif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
        <div>
            <x-sys.button type="delete" 
                        class="ajax-delete"
                        :data-url="route('labs.destroy', $lab->encrypted_lab_id)"
                        data-title="Hapus Lab"
                        data-text="Apakah Anda yakin ingin menghapus laboratorium ini? Seluruh data terkait akan terpengaruh."
                        data-redirect="{{ route('labs.index') }}" />
        </div>
        <div>
            <x-sys.button type="back" :href="route('labs.index')" />
        </div>
    </div>
@endsection
