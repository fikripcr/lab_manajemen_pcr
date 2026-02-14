@extends('layouts.admin.app')

@section('title', 'Isi Log Penggunaan Lab')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Isi Log Penggunaan Lab (Tamu / Peserta)
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.log-lab.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('lab.log-lab.store') }}" method="POST" class="ajax-form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="mb-3">
                                <label class="form-label">Kegiatan (Opsional)</label>
                                <select name="kegiatan_id" class="form-select select2-offline">
                                    <option value="">-- Pilih Kegiatan Hari Ini --</option>
                                    @foreach($activeKegiatans as $kegiatan)
                                        <option value="{{ encryptId($kegiatan->kegiatan_id) }}">
                                            {{ $kegiatan->nama_kegiatan }} ({{ $kegiatan->jam_mulai->format('H:i') }} - {{ $kegiatan->jam_selesai->format('H:i') }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-hint">Jika anda mengikuti kegiatan, pilih di sini. Lab akan terpilih otomatis.</small>
                            </div>

                            <div class="hr-text">ATAU</div>

                            <div class="mb-3">
                                <label class="form-label">Lab (Jika tidak ada kegiatan spesifik)</label>
                                <select name="lab_id" class="form-select select2-offline">
                                    <option value="">-- Pilih Lab --</option>
                                    @foreach($labs as $lab)
                                        <option value="{{ encryptId($lab->lab_id) }}">{{ $lab->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Nama Lengkap</label>
                                <input type="text" name="nama_peserta" class="form-control" placeholder="Nama Peserta" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">NPM / NIK (Opsional)</label>
                                        <input type="text" name="npm_peserta" class="form-control" placeholder="Nomor Induk">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email (Opsional)</label>
                                        <input type="email" name="email_peserta" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nomor PC (Jika menggunakan)</label>
                                <input type="number" name="nomor_pc" class="form-control" placeholder="Contoh: 10">
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Kondisi PC / Alat</label>
                                <select name="kondisi" class="form-select" required>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak">Rusak / Bermasalah</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan Tambahan</label>
                                <textarea name="catatan_umum" class="form-control" rows="2"></textarea>
                            </div>

                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Simpan Log</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


