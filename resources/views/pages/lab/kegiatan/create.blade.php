@extends('layouts.admin.app')

@section('title', 'Ajukan Peminjaman Lab')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Detail Peminjaman & Kegiatan / Event
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.kegiatan.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('lab.kegiatan.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            
                            <x-tabler.form-input name="nama_kegiatan" label="Nama Kegiatan" placeholder="Contoh: Workshop Laravel" required />

                            <div class="mb-3">
                                <x-tabler.form-select name="lab_id" label="Lab Target" :options="$labs->mapWithKeys(fn($lab) => [encryptId($lab->lab_id) => $lab->name . ' (Kapasitas: ' . $lab->capacity . ')'])->toArray()" placeholder="Pilih Lab" required />
                            </div>


                            <div class="row">
                                <div class="col-md-4">
                                    <x-tabler.form-input type="date" name="tanggal" label="Tanggal" required />
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-input type="time" name="jam_mulai" label="Jam Mulai" required />
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-input type="time" name="jam_selesai" label="Jam Selesai" required />
                                </div>
                            </div>

                            <x-tabler.form-textarea name="deskripsi" label="Deskripsi" rows="4" placeholder="Jelaskan tujuan dan detail kegiatan..." required />

                            <x-tabler.form-input type="file" name="dokumentasi_path" label="Dokumen Pendukung (Surat Permohonan)" accept=".pdf,.jpg,.jpeg,.png" help="Max 2MB. Disarankan format PDF." />

                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


