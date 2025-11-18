@extends('layouts.admin.app')

@section('title', 'Team Lab: ' . $lab->name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Team Lab: {{ $lab->name }}</h4>
                    <a href="{{ route('labs.teams.create', $lab->encrypted_lab_id) }}" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Tambah Anggota
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Jabatan</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($labTeams as $team)
                                    <tr>
                                        <td>{{ $team->user->name }}</td>
                                        <td>{{ $team->user->email }}</td>
                                        <td>{{ $team->jabatan ?? '-' }}</td>
                                        <td>{{ formatTanggalIndo($team->tanggal_mulai) }}</td>
                                        <td>
                                            <span class="badge {{ $team->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                                {{ $team->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('labs.teams.destroy', [$lab->encrypted_lab_id, $team->encrypted_id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini dari tim lab?')">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data anggota tim</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $labTeams->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
