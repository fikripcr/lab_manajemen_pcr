@extends('layouts.admin.app')

@section('title', 'Daftar Assignment PC')

@section('content')
<div class="container-xl">
    <div class="row">
        <div class="col-12">
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            Jadwal Kuliah
                        </div>
                        <h2 class="page-title">
                            Assignment PC: {{ $jadwal->mataKuliah->nama_mk ?? '-' }}
                        </h2>
                        <div class="text-muted mt-1">
                            {{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }} |
                            {{ $jadwal->lab->name ?? 'Lab ?' }} |
                            {{ $jadwal->dosen->name ?? 'Dosen ?' }}
                        </div>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('lab.jadwal.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-2"></i> Kembali
                            </a>
                            <a href="{{ route('lab.jadwal.assignments.create', encryptId($jadwal->jadwal_kuliah_id)) }}" class="btn btn-primary">
                                <i class="bx bx-plus me-2"></i> Tambah Assignment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="table-assignments">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NPM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Nomor PC</th>
                                <th>Nomor Loker</th>
                                <th>Status</th>
                                <th class="w-1">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        var table = $('#table-assignments').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('lab.jadwal.assignments.data', encryptId($jadwal->jadwal_kuliah_id)) }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'mahasiswa_npm', name: 'user.username' },
                { data: 'mahasiswa_nama', name: 'user.name' },
                { data: 'nomor_pc', name: 'nomor_pc' },
                { data: 'nomor_loker', name: 'nomor_loker' },
                { 
                    data: 'is_active', 
                    name: 'is_active',
                    render: function(data, type, row) {
                        return data == 1 
                            ? '<span class="badge bg-success">Aktif</span>' 
                            : '<span class="badge bg-secondary">Non-Aktif</span>';
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });

    function deleteAssignment(url) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data assignment akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Terhapus!', response.message, 'success');
                            $('#table-assignments').DataTable().ajax.reload();
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
