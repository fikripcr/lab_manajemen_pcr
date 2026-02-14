@extends('layouts.admin.app')

@section('title', 'Tambah Assignment PC')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Jadwal Kuliah
                </div>
                <h2 class="page-title">
                    Tambah Assignment PC
                </h2>
                <div class="text-muted mt-1">
                    {{ $jadwal->mataKuliah->nama_mk ?? '-' }} - Kelas {{ $jadwal->hari }}
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.jadwal.assignments.index', encryptId($jadwal->jadwal_kuliah_id)) }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="row">
            <div class="col-md-6">
                <form id="form-assignment" action="{{ route('lab.jadwal.assignments.store', encryptId($jadwal->jadwal_kuliah_id)) }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Assignment</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Mahasiswa</label>
                                <select name="user_id" class="form-select select2" required>
                                    <option value="">Pilih Mahasiswa</option>
                                    @foreach($mahasiswas as $mhs)
                                        <option value="{{ $mhs->id }}">{{ $mhs->name }} ({{ $mhs->username }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Nomor PC</label>
                                        <select name="nomor_pc" class="form-select select2" required>
                                            <option value="">Pilih PC</option>
                                            @for($i = 1; $i <= $totalPc; $i++)
                                                <option value="{{ $i }}" {{ in_array($i, $assignedPcs) ? 'disabled' : '' }}>
                                                    PC {{ str_pad($i, 2, '0', STR_PAD_LEFT) }} {{ in_array($i, $assignedPcs) ? '(Terpakai)' : '' }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Loker</label>
                                        <input type="number" name="nomor_loker" class="form-control" placeholder="Opsional">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Simpan Assignment</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Denah / List PC</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @for($i = 1; $i <= $totalPc; $i++)
                                @php
                                    $isTaken = in_array($i, $assignedPcs);
                                    $color = $isTaken ? 'danger' : 'success';
                                @endphp
                                <div class="avatar bg-{{ $color }}-lt" title="PC {{ $i }} {{ $isTaken ? 'Terpakai' : 'Kosong' }}">
                                    {{ $i }}
                                </div>
                            @endfor
                        </div>
                        <div class="mt-3 text-muted">
                            <span class="badge bg-success-lt me-1">1</span> Kosong
                            <span class="badge bg-danger-lt ms-2 me-1">1</span> Terpakai
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5' // Asumsi pakai theme bootstrap-5
        });

        $('#form-assignment').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                beforeSend: function() {
                    btn.prop('disabled', true).addClass('btn-loading');
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = response.redirect;
                        });
                    } else {
                        Swal.fire('Gagal!', response.message, 'error');
                        btn.prop('disabled', false).removeClass('btn-loading');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan.';
                    if (xhr.status === 422) {
                        errorMessage = xhr.responseJSON.message || 'Validasi gagal.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('Gagal!', errorMessage, 'error');
                    btn.prop('disabled', false).removeClass('btn-loading');
                }
            });
        });
    });
</script>
@endpush
