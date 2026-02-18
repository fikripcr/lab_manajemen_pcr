@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Tambah Personil Baru" pretitle="Personil" />
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <form class="ajax-form" action="{{ route('shared.personil.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <x-tabler.form-input 
                                name="nip" 
                                label="NIP/NIK" 
                                placeholder="Masukkan NIP/NIK" 
                            />
                        </div>
                        <div class="col-md-6">
                            <x-tabler.form-input 
                                name="nama" 
                                label="Nama Lengkap" 
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
                                placeholder="Email" 
                            />
                        </div>
                        <div class="col-md-6">
                            <x-tabler.form-input 
                                name="posisi" 
                                label="Posisi/Jabatan" 
                                placeholder="Contoh: Security, Janitor, Teknisi" 
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-tabler.form-select 
                                name="org_unit_id"
                                label="Unit Kerja / Organisasi"
                                :options="$units->pluck('name', 'orgunit_id')->toArray()"
                                placeholder="Pilih Unit Kerja"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-tabler.form-input 
                                name="vendor" 
                                label="Vendor / Pembawa" 
                                placeholder="Nama Perusahaan Vendor" 
                            />
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status_aktif" checked>
                            <span class="form-check-label">Personil Aktif</span>
                        </label>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <x-tabler.button type="submit" text="Simpan Personil" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
