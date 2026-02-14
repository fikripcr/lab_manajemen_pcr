@extends('layouts.admin.app')

@section('title', 'Ajukan Surat Bebas Lab')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Pengajuan Surat Bebas Lab
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.surat-bebas.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('lab.surat-bebas.store') }}" method="POST" class="ajax-form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-info">
                                Pastikan anda <strong>TIDAK</strong> memiliki tanggungan peminjaman alat atau masalah administrasi lab lainnya sebelum mengajukan surat ini.
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Mahasiswa</label>
                                <div class="form-control-plaintext font-weight-bold">{{ auth()->user()->name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan Tambahan (Opsional)</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Keterangan tambahan jika diperlukan..."></textarea>
                            </div>

                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
