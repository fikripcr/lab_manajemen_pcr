@extends('layouts.tabler.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Edit Mahasiswa
            </h2>
            <div class="text-muted mt-1">Master Data / Mahasiswa / Edit</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <x-tabler.button type="back" href="{{ route('lab.mahasiswa.index') }}" />
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Data Mahasiswa</h3>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <form class="ajax-form" action="{{ route('lab.mahasiswa.update', $mahasiswa) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="nim" 
                            label="NIM" 
                            type="text" 
                            value="{{ old('nim', $mahasiswa->nim) }}"
                            placeholder="Masukkan NIM" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="nama" 
                            label="Nama Mahasiswa" 
                            type="text" 
                            value="{{ old('nama', $mahasiswa->nama) }}"
                            placeholder="Masukkan Nama Lengkap" 
                            required="true" 
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="email" 
                            label="Email" 
                            type="email" 
                            value="{{ old('email', $mahasiswa->email) }}"
                            placeholder="mahasiswa@example.com" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-select 
                            name="orgunit_id" 
                            label="Program Studi"
                            :options="$prodiList->mapWithKeys(fn($prodi) => [$prodi->orgunit_id => $prodi->name])->toArray()"
                            :selected="old('orgunit_id', $mahasiswa->orgunit_id)"
                            placeholder="-- Pilih Program Studi --"
                            required
                        />
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <x-tabler.button type="cancel" href="{{ route('lab.mahasiswa.index') }}" />
                    <x-tabler.button type="submit" text="Simpan Perubahan" />
                </div>
                </div>
            </form>
        </div>
    </div>
@endsection
