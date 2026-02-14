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
                            
                            <div class="mb-3">
                                <label class="form-label required">Nama Kegiatan</label>
                                <input type="text" name="nama_kegiatan" class="form-control" placeholder="Contoh: Workshop Laravel" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Lab Target</label>
                                <select name="lab_id" class="form-select select2-offline" required>
                                    <option value="">Pilih Lab</option>
                                    @foreach($labs as $lab)
                                        <option value="{{ encryptId($lab->lab_id) }}">{{ $lab->name }} (Kapasitas: {{ $lab->capacity }})</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label required">Tanggal</label>
                                        <input type="date" name="tanggal" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label required">Jam Mulai</label>
                                        <input type="time" name="jam_mulai" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label required">Jam Selesai</label>
                                        <input type="time" name="jam_selesai" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan tujuan dan detail kegiatan..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dokumen Pendukung (Surat Permohonan)</label>
                                <input type="file" name="dokumentasi_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="form-hint">Max 2MB. Disarankan format PDF.</small>
                            </div>

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


