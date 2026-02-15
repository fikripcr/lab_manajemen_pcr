@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Edit Personil
            </h2>
            <div class="text-muted mt-1">Master Data / Personil / Edit</div>
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
            <h3 class="card-title">Edit Data Personil</h3>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <form class="ajax-form" action="{{ route('lab.personil.update', $personil) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="nip" 
                            label="NIP/NIK" 
                            type="text" 
                            value="{{ old('nip', $personil->nip) }}"
                            placeholder="Masukkan NIP/NIK" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="nama" 
                            label="Nama Personil" 
                            type="text" 
                            value="{{ old('nama', $personil->nama) }}"
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
                            value="{{ old('email', $personil->email) }}"
                            placeholder="personil@example.com" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="jabatan" 
                            label="Jabatan" 
                            type="text" 
                            value="{{ old('jabatan', $personil->jabatan) }}"
                            placeholder="Kepala Lab" 
                            required="true" 
                        />
                    </div>
                </div>

                    <x-tabler.button type="cancel" href="{{ route('lab.personil.index') }}" />
                    <x-tabler.button type="submit" text="Simpan Perubahan" />
                </div>
            </form>
        </div>
    </div>
@endsection
