@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Tambah Personil
            </h2>
            <div class="text-muted mt-1">Master Data / Personil / Tambah</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <x-tabler.button type="back" href="{{ route('lab.personil.index') }}" />
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Data Personil</h3>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <form class="ajax-form" action="{{ route('lab.personil.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="nip" 
                            label="NIP/NIK" 
                            type="text" 
                            value="{{ old('nip') }}"
                            placeholder="Masukkan NIP/NIK" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="nama" 
                            label="Nama Personil" 
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
                            placeholder="personil@example.com" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="posisi" 
                            label="Posisi" 
                            type="text" 
                            value="{{ old('posisi') }}"
                            placeholder="Kepala Lab" 
                            required="true" 
                        />
                    </div>
                </div>

                    <x-tabler.button type="cancel" href="{{ route('lab.personil.index') }}" />
                    <x-tabler.button type="submit" text="Simpan" />
                </div>
            </form>
        </div>
    </div>
@endsection
