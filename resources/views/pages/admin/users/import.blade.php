@extends('layouts.admin.app')

@section('title', 'Import User')

@section('header')
    <x-sys.page-header title="Import Pengguna" pretitle="Pengguna">
        <x-slot:actions>
            <x-sys.button type="back" :href="route('users.index')" />
        </x-slot:actions>
    </x-sys.page-header>
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

                    <form action="{{ route('users.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="file">Pilih File</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror"
                                   id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="role_default">Role Default</label>
                            <select class="form-select @error('role_default') is-invalid @enderror"
                                    id="role_default" name="role_default">
                                <option value="">Pilih role default jika tidak disertakan di file</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('role_default')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('overwrite_existing') is-invalid @enderror"
                                   id="overwrite_existing" name="overwrite_existing" value="1">
                            <label class="form-check-label" for="overwrite_existing">
                                Timpa data pengguna yang sudah ada (berdasarkan email)
                            </label>
                            @error('overwrite_existing')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <x-sys.button type="submit" text="Import Users" icon="ti ti-upload" />
                            <x-sys.button type="cancel" :href="route('users.index')" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
