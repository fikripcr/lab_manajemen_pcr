@extends('layouts.tabler.app')

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
                            <x-tabler.button :href="route('lab.jadwal.index')" class="btn-secondary" icon="bx bx-arrow-back" text="Kembali" />
                            <x-tabler.button :href="route('lab.jadwal.assignments.create', encryptId($jadwal->jadwal_kuliah_id))" class="btn-primary" icon="bx bx-plus" text="Tambah Assignment" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-assignments"
                    route="{{ route('lab.jadwal.assignments.data', encryptId($jadwal->jadwal_kuliah_id)) }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'mahasiswa_npm', 'name' => 'user.username', 'title' => 'NPM'],
                        ['data' => 'mahasiswa_nama', 'name' => 'user.name', 'title' => 'Nama Mahasiswa'],
                        ['data' => 'nomor_pc', 'name' => 'nomor_pc', 'title' => 'Nomor PC'],
                        ['data' => 'nomor_loker', 'name' => 'nomor_loker', 'title' => 'Nomor Loker'],
                        ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'render' => 'function(data){ return data == 1 ? \'<span class="badge bg-success text-white">Aktif</span>\' : \'<span class="badge bg-secondary text-white">Non-Aktif</span>\'; }'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
                    ]"
                />
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // x-tabler.datatable initializes the table.
    // Custom delete logic needs to be globally available or attached via delegation.
    
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
                            // Reload using the instance stored in window
                            if(window['DT_table-assignments']) {
                                window['DT_table-assignments'].ajax.reload();
                            } else {
                                // Fallback if old way is still needed (unlikely if standardized correctly)
                                location.reload(); 
                            }
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
