@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
<x-tabler.page-header title="Tambah Pegawai" pretitle="Manajemen Kepegawaian">
    <x-slot:actions>
        <x-tabler.button href="{{ route('hr.pegawai.index') }}" style="secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<form action="{{ route('hr.pegawai.store') }}" method="POST" id="form-pegawai" class="ajax-form" enctype="multipart/form-data">
    @csrf
    
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#tab-datadiri" class="nav-link active" data-bs-toggle="tab">Data Diri</a>
                </li>
                <li class="nav-item">
                    <a href="#tab-kepegawaian" class="nav-link" data-bs-toggle="tab">Informasi Kepegawaian</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Tab Data Diri -->
                <div class="tab-pane active show" id="tab-datadiri">
                    <div class="row">
                        <div class="col-md-6">
                            <x-tabler.form-input name="nama" label="Nama Lengkap" placeholder="Nama Lengkap dengan Gelar" required="true" />
                        </div>
                        <div class="col-md-3">
                            <x-tabler.form-input name="inisial" label="Inisial" placeholder="Ex: ABC" />
                        </div>
                        <div class="col-md-3">
                            <x-tabler.form-input name="nip" label="NIP / NIK" placeholder="Nomor Induk Pegawai" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-tabler.form-input name="tempat_lahir" label="Tempat Lahir" placeholder="Kota Kelahiran" />
                        </div>
                        <div class="col-md-6">
                            <x-tabler.form-input name="tgl_lahir" label="Tanggal Lahir" type="date" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-tabler.form-input name="email" label="Email" type="email" placeholder="email@pcr.ac.id" />
                        </div>
                        <div class="col-md-6">
                            <x-tabler.form-input name="no_hp" label="Nomor HP" placeholder="0812..." />
                        </div>
                    </div>
                    <div class="mb-3">
                        <x-tabler.form-textarea name="alamat" label="Alamat Lengkap" rows="3" />
                    </div>
                </div>

                <!-- Tab Kepegawaian -->
                <div class="tab-pane" id="tab-kepegawaian">
                    <div class="row">
                        <div class="col-md-6">
                            <x-tabler.form-select name="statpegawai_id" label="Status Pegawai" required="true">
                                <option value="">Pilih Status Pegawai</option>
                                @foreach($statusPegawai as $sp)
                                    <option value="{{ $sp->statpegawai_id }}">{{ $sp->statpegawai }}</option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-6">
                            <x-tabler.form-select name="stataktifitas_id" label="Status Aktifitas" required="true">
                                <option value="">Pilih Status Aktifitas</option>
                                @foreach($statusAktifitas as $sa)
                                    <option value="{{ $sa->stataktifitas_id }}">{{ $sa->stataktifitas }}</option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                    </div>
                    <div class="alert alert-info py-2">
                        <i class="ti ti-info-circle me-1"></i>
                        Penugasan ke unit/jabatan dapat dilakukan setelah pegawai tersimpan melalui menu <strong>Riwayat Penugasan</strong>.
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <x-tabler.button type="submit" style="primary" icon="ti ti-device-floppy" text="Simpan Pegawai" />
        </div>
    </div>
</form>
@endsection
@endsection
