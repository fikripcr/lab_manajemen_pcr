@extends('layouts.admin.app')

@section('title', 'Inventaris Lab: ' . $lab->name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Inventaris Lab: {{ $lab->name }}</h4>
                    <a href="{{ route('labs.inventaris.create', $lab->lab_id) }}" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Tambah Inventaris
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
                                    <th>Kode Inventaris</th>
                                    <th>Nama Alat</th>
                                    <th>Jenis Alat</th>
                                    <th>Kondisi Terakhir</th>
                                    <th>No Series</th>
                                    <th>Tanggal Penempatan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($labInventaris as $item)
                                    <tr>
                                        <td>{{ $item->kode_inventaris }}</td>
                                        <td>{{ $item->inventaris->nama_alat }}</td>
                                        <td>{{ $item->inventaris->jenis_alat }}</td>
                                        <td>{{ $item->inventaris->kondisi_terakhir }}</td>
                                        <td>{{ $item->no_series }}</td>
                                        <td>{{ \App\Helpers\Helper::formatTanggalIndo($item->tanggal_penempatan) }}</td>
                                        <td>
                                            <span class="badge 
                                                {{ $item->status === 'active' ? 'bg-label-success' : 
                                                   ($item->status === 'moved' ? 'bg-label-warning' : 'bg-label-secondary') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('labs.inventaris.destroy', [$lab->lab_id, $item->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus inventaris ini dari lab?')">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data inventaris</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $labInventaris->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection