@extends('layouts.tabler.app')

@section('title', 'Isi Log Penggunaan Lab')

@section('content')
<div class="container-xl">
    <x-tabler.page-header title="Isi Log Penggunaan Lab" pretitle="Buku Tamu">
        <x-slot:actions>
            <x-tabler.button type="back" href="{{ route('lab.log-lab.index') }}" />
        </x-slot:actions>
    </x-tabler.page-header>

    <div class="page-body">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('lab.log-lab.store') }}" method="POST" class="ajax-form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            
                            <x-tabler.form-select name="kegiatan_id" label="Kegiatan (Opsional)" :options="$activeKegiatans->mapWithKeys(fn($k) => [encryptId($k->kegiatan_id) => $k->nama_kegiatan . ' (' . $k->jam_mulai->format('H:i') . ' - ' . $k->jam_selesai->format('H:i') . ')'])->toArray()" placeholder="-- Pilih Kegiatan Hari Ini --" class="select2-offline mb-3" help="Jika anda mengikuti kegiatan, pilih di sini. Lab akan terpilih otomatis." />

                            <div class="hr-text">ATAU</div>

                            <x-tabler.form-select name="lab_id" label="Lab (Jika tidak ada kegiatan spesifik)" :options="$labs->mapWithKeys(fn($lab) => [encryptId($lab->lab_id) => $lab->name])->toArray()" placeholder="-- Pilih Lab --" class="select2-offline mb-3" />

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

                            <x-tabler.form-select name="kondisi" label="Kondisi PC / Alat" :options="['Baik' => 'Baik', 'Rusak' => 'Rusak / Bermasalah']" placeholder="Pilih Kondisi" required />

                            <x-tabler.form-textarea name="catatan_umum" label="Catatan Tambahan" rows="2" />

                        </div>
                        <div class="card-footer text-end">
                            <x-tabler.button type="submit" text="Simpan Log" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


