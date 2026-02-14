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
                                <x-tabler.form-select name="kegiatan_id" label="Kegiatan (Opsional)" :options="$activeKegiatans->mapWithKeys(fn($k) => [encryptId($k->kegiatan_id) => $k->nama_kegiatan . ' (' . $k->jam_mulai->format('H:i') . ' - ' . $k->jam_selesai->format('H:i') . ')'])->toArray()" placeholder="-- Pilih Kegiatan Hari Ini --" class="select2-offline" help="Jika anda mengikuti kegiatan, pilih di sini. Lab akan terpilih otomatis." />
                            </div>

                            <div class="hr-text">ATAU</div>

                            <div class="mb-3">
                                <x-tabler.form-select name="lab_id" label="Lab (Jika tidak ada kegiatan spesifik)" :options="$labs->mapWithKeys(fn($lab) => [encryptId($lab->lab_id) => $lab->name])->toArray()" placeholder="-- Pilih Lab --" class="select2-offline" />
                            </div>

                            <x-tabler.form-input name="nama_peserta" label="Nama Lengkap" placeholder="Nama Peserta" required />

                            <div class="row">
                                <div class="col-md-6">
                                    <x-tabler.form-input name="npm_peserta" label="NPM / NIK (Opsional)" placeholder="Nomor Induk" />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input type="email" name="email_peserta" label="Email (Opsional)" placeholder="Email" />
                                </div>
                            </div>

                            <x-tabler.form-input type="number" name="nomor_pc" label="Nomor PC (Jika menggunakan)" placeholder="Contoh: 10" />

                            <div class="mb-3">
                                <x-tabler.form-select name="kondisi" label="Kondisi PC / Alat" :options="['Baik' => 'Baik', 'Rusak' => 'Rusak / Bermasalah']" placeholder="Pilih Kondisi" required />
                            </div>

                            <x-tabler.form-textarea name="catatan_umum" label="Catatan Tambahan" rows="2" />

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


