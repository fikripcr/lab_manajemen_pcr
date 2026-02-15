@extends('layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Import Pengguna" pretitle="Pengguna">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.users.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-tabler.flash-message />

            <div class="card mb-4">
                <div class="card-body">
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-info-circle fs-2 me-2"></i>
                            </div>
                            <div>
                                <h4 class="alert-title">Petunjuk Import Pengguna</h4>
                                <div class="text-muted">
                                    Gunakan format file Excel (.xlsx, .xls) atau CSV dengan struktur kolom sebagai berikut:
                                    <ul class="mt-2 mb-2">
                                        <li><strong>Nama:</strong> Nama lengkap pengguna</li>
                                        <li><strong>Email:</strong> Alamat email pengguna (unik)</li>
                                        <li><strong>Password:</strong> Sandi pengguna (opsional, akan di-generate jika kosong)</li>
                                        <li><strong>Role:</strong> Nama role pengguna (contoh: admin, dosen, mahasiswa, dll)</li>
                                    </ul>
                                    <a href="{{ Vite::asset('resources/assets/templates/template_import_user.xlsx') }}" class="btn btn-sm btn-outline-info">
                                        <i class="ti ti-download me-1"></i> Download Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('lab.users.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <x-tabler.form-input type="file" name="file" label="Pilih File" class="filepond-input" accept=".xlsx,.xls,.csv" required help="Format yang didukung: .xlsx, .xls, .csv" />

                        <div class="mb-3">
                            <x-tabler.form-select
                                id="role_default"
                                name="role_default"
                                label="Role Default"
                                placeholder="Pilih role default jika tidak disertakan di file"
                                :options="$roles->pluck('name', 'name')->toArray()"
                                type="select2"
                            />
                            @error('role_default')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <x-tabler.form-checkbox 
                                name="overwrite_existing" 
                                label="Timpa data pengguna yang sudah ada (berdasarkan email)" 
                                value="1" 
                            />
                        </div>

                        <div class="mb-3">
                            <x-tabler.button type="import" text="Import Users" />
                            <x-tabler.button type="cancel" :href="route('lab.users.index')" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


