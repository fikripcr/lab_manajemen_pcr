@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
<x-tabler.page-header title="Edit Data Pegawai" pretitle="Manajemen Kepegawaian">
    <x-slot:actions>
        <x-tabler.button href="{{ route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) }}" style="secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<form action="{{ route('hr.pegawai.update', $pegawai->encrypted_pegawai_id) }}" method="POST" id="form-pegawai-edit" class="ajax-form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="card">
        <div class="alert alert-info m-3 py-2">
            <i class="ti ti-info-circle me-1"></i>
            Setiap perubahan data diri akan disimpan sebagai <strong>Draft / Menunggu Persetujuan</strong> dan tidak akan langsung mengubah data aktif sampai disetujui oleh Administrator.
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <x-tabler.form-input name="nama" label="Nama Lengkap" value="{{ old('nama', $pegawai->latestDataDiri->nama ?? $pegawai->nama) }}" required="true" />
                </div>
                <div class="col-md-3">
                    <x-tabler.form-input name="inisial" label="Inisial" value="{{ old('inisial', $pegawai->latestDataDiri->inisial ?? $pegawai->inisial) }}" />
                </div>
                <div class="col-md-3">
                    <x-tabler.form-input name="nip" label="NIP / NIK" value="{{ old('nip', $pegawai->latestDataDiri->nip ?? $pegawai->nip) }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-tabler.form-input name="tempat_lahir" label="Tempat Lahir" value="{{ old('tempat_lahir', $pegawai->latestDataDiri->tempat_lahir ?? '') }}" />
                </div>
                <div class="col-md-6">
                    <x-tabler.form-input name="tgl_lahir" label="Tanggal Lahir" type="date" value="{{ old('tgl_lahir', optional($pegawai->latestDataDiri->tgl_lahir)->format('Y-m-d')) }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-tabler.form-input name="email" label="Email" type="email" value="{{ old('email', $pegawai->latestDataDiri->email ?? $pegawai->email) }}" />
                </div>
                <div class="col-md-6">
                    <x-tabler.form-input name="no_hp" label="Nomor HP" value="{{ old('no_hp', $pegawai->latestDataDiri->no_hp ?? '') }}" />
                </div>
            </div>

            <div class="mb-3">
                <x-tabler.form-textarea name="alamat" label="Alamat Lengkap" rows="3" value="{{ old('alamat', $pegawai->latestDataDiri->alamat ?? '') }}" />
            </div>

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

        </div>
        <div class="card-footer text-end">
            <x-tabler.button type="submit" style="primary" icon="ti ti-check" text="Ajukan Perubahan" />
        </div>
    </div>
</form>
@endsection
@endsection
