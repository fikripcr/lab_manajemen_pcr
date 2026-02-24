@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.tabler.empty' : 'layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ isset($pegawai) && $pegawai->exists ? 'Edit Data Pegawai' : 'Tambah Pegawai' }}" pretitle="Manajemen Kepegawaian">
    <x-slot:actions>
        <x-tabler.button href="{{ isset($pegawai) && $pegawai->exists ? route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) : route('hr.pegawai.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<form action="{{ isset($pegawai) && $pegawai->exists ? route('hr.pegawai.update', $pegawai->encrypted_pegawai_id) : route('hr.pegawai.store') }}" method="POST" id="form-pegawai-{{ isset($pegawai) && $pegawai->exists ? 'edit' : 'create' }}" class="ajax-form" enctype="multipart/form-data">
    @csrf
    @if(isset($pegawai) && $pegawai->exists)
        @method('PUT')
    @endif
    
    <div class="card">
        @if(isset($pegawai) && $pegawai->exists)
            <div class="alert alert-info m-3 py-2">
                <i class="ti ti-info-circle me-1"></i>
                Setiap perubahan data diri akan disimpan sebagai <strong>Draft / Menunggu Persetujuan</strong> dan tidak akan langsung mengubah data aktif sampai disetujui oleh Administrator.
            </div>
        @else
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
        @endif

        <div class="card-body">
            @if(!isset($pegawai) || !$pegawai->exists)
                <div class="tab-content">
                    <!-- Tab Data Diri -->
                    <div class="tab-pane active show" id="tab-datadiri">
            @endif

                        <div class="row">
                            <div class="col-md-6">
                                <x-tabler.form-input name="nama" label="Nama Lengkap" placeholder="Nama Lengkap dengan Gelar" value="{{ old('nama', $pegawai->latestDataDiri->nama ?? $pegawai->nama ?? '') }}" required="true" />
                            </div>
                            <div class="col-md-3">
                                <x-tabler.form-input name="inisial" label="Inisial" placeholder="Ex: ABC" value="{{ old('inisial', $pegawai->latestDataDiri->inisial ?? $pegawai->inisial ?? '') }}" />
                            </div>
                            <div class="col-md-3">
                                <x-tabler.form-input name="nip" label="NIP / NIK" placeholder="Nomor Induk Pegawai" value="{{ old('nip', $pegawai->latestDataDiri->nip ?? $pegawai->nip ?? '') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-tabler.form-input name="tempat_lahir" label="Tempat Lahir" placeholder="Kota Kelahiran" value="{{ old('tempat_lahir', $pegawai->latestDataDiri->tempat_lahir ?? '') }}" />
                            </div>
                            <div class="col-md-6">
                                <x-tabler.form-input name="tgl_lahir" label="Tanggal Lahir" type="date" value="{{ old('tgl_lahir', isset($pegawai) && $pegawai->latestDataDiri->tgl_lahir ? $pegawai->latestDataDiri->tgl_lahir->format('Y-m-d') : '') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-tabler.form-input name="email" label="Email" type="email" placeholder="email@pcr.ac.id" value="{{ old('email', $pegawai->latestDataDiri->email ?? $pegawai->email ?? '') }}" />
                            </div>
                            <div class="col-md-6">
                                <x-tabler.form-input name="no_hp" label="Nomor HP" placeholder="0812..." value="{{ old('no_hp', $pegawai->latestDataDiri->no_hp ?? '') }}" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <x-tabler.form-textarea name="alamat" label="Alamat Lengkap" rows="3" value="{{ old('alamat', $pegawai->latestDataDiri->alamat ?? '') }}" />
                        </div>

            @if(isset($pegawai) && $pegawai->exists)
                        <h3 class="card-title mt-4">Informasi Kepegawaian</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <x-tabler.form-select name="orgunit_departemen_id" label="Unit / Departemen">
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departemen as $d)
                                        <option value="{{ $d->orgunit_id }}" {{ (old('orgunit_departemen_id', $pegawai->latestDataDiri->orgunit_departemen_id ?? '') == $d->orgunit_id) ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </x-tabler.form-select>
                            </div>
                            <div class="col-md-6">
                                <x-tabler.form-select name="orgunit_posisi_id" label="Posisi">
                                    <option value="">Pilih Posisi</option>
                                    @foreach($posisi as $p)
                                        <option value="{{ $p->orgunit_id }}" {{ (old('orgunit_posisi_id', $pegawai->latestDataDiri->orgunit_posisi_id ?? '') == $p->orgunit_id) ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </x-tabler.form-select>
                            </div>
                        </div>
            @else
                    </div>

                    <!-- Tab Kepegawaian (Hanya untuk Create) -->
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
                            Penugasan ke unit/jabatan dapat dilakukan setelah pegawai tersimpan melalui menu <strong>Penugasan</strong>.
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="card-footer text-end">
            <x-tabler.button type="submit" style="primary" icon="{{ isset($pegawai) && $pegawai->exists ? 'ti ti-check' : 'ti ti-device-floppy' }}" text="{{ isset($pegawai) && $pegawai->exists ? 'Ajukan Perubahan' : 'Simpan Pegawai' }}" />
        </div>
    </div>
</form>
@endsection
