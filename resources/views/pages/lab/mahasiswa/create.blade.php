@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Tambah Mahasiswa
            </h2>
            <div class="text-muted mt-1">Master Data / Mahasiswa / Tambah</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('lab.mahasiswa.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
                    <i class="ti ti-arrow-left me-1"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Data Mahasiswa</h3>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <form class="ajax-form" action="{{ route('lab.mahasiswa.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="nim" 
                            label="NIM" 
                            type="text" 
                            value="{{ old('nim') }}"
                            placeholder="Masukkan NIM" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="nama" 
                            label="Nama Mahasiswa" 
                            type="text" 
                            value="{{ old('nama') }}"
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
                            value="{{ old('email') }}"
                            placeholder="mahasiswa@example.com" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="program_studi" 
                            label="Program Studi" 
                            type="text" 
                            value="{{ old('program_studi') }}"
                            placeholder="Teknik Informatika" 
                            required="true" 
                        />
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('lab.mahasiswa.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Batal
                    </a>
                    <x-tabler.button type="submit" text="Simpan" />
                </div>
            </form>
        </div>
    </div>
@endsection
