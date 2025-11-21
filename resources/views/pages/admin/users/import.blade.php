@extends('layouts.admin.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Import Users /</span> Upload File</h4>

    <div class="row">
        <div class="col-12">
            <x-flash-message />

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Import Users from Excel</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class='bx bx-arrow-back me-1'></i> Back to Users
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Petunjuk Import Pengguna</h6>
                        <p>Gunakan format file Excel (.xlsx, .xls) atau CSV dengan struktur kolom sebagai berikut:</p>
                        <ul>
                            <li><strong>Nama:</strong> Nama lengkap pengguna</li>
                            <li><strong>Email:</strong> Alamat email pengguna (unik)</li>
                            <li><strong>Password:</strong> Sandi pengguna (opsional, akan di-generate jika kosong)</li>
                            <li><strong>Role:</strong> Nama role pengguna (contoh: admin, dosen, mahasiswa, dll)</li>
                        </ul>
                        <a href="{{ asset('assets-admin/import/template_import_user.xlsx') }}" class="btn btn-primary btn-sm">
                            <i class='bx bx-download me-1'></i> Download Template
                        </a>
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
                            <button type="submit" class="btn btn-primary me-2">
                                <i class='bx bx-import me-1'></i> Import Users
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class='bx bx-x me-1'></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
