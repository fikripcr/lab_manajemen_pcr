@extends('layouts.tabler.app')

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
                <x-tabler.button type="back" href="{{ route('lab.surat-bebas.index') }}" />
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

                            <x-tabler.form-textarea name="remarks" label="Catatan Tambahan (Opsional)" rows="3" placeholder="Keterangan tambahan jika diperlukan..." />

                        </div>
                        <div class="card-footer text-end">
                            <x-tabler.button type="submit" class="btn-primary" text="Kirim Pengajuan" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
