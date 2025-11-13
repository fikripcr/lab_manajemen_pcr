@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Import Jadwal</h4>
        <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    @include('components.flash-message')

                    <div class="alert alert-info">
                        <h6 class="alert-heading">Petunjuk Import Jadwal</h6>
                        <p>Gunakan format file Excel (.xlsx) dengan struktur kolom sebagai berikut:</p>
                        <ul>
                            <li><strong>tahun_ajaran</strong> - Format: YYYY/YYYY (contoh: 2023/2024)</li>
                            <li><strong>semester</strong> - 1 (Ganjil) atau 2 (Genap)</li>
                            <li><strong>kode_mk</strong> - Kode mata kuliah (contoh: IF101)</li>
                            <li><strong>dosen</strong> - Nama atau email dosen</li>
                            <li><strong>hari</strong> - Hari perkuliahan (contoh: Senin, Selasa)</li>
                            <li><strong>jam_mulai</strong> - Format: HH:MM (contoh: 08:00)</li>
                            <li><strong>jam_selesai</strong> - Format: HH:MM (contoh: 10:00)</li>
                            <li><strong>lab</strong> - Nama lab (contoh: Lab Jaringan)</li>
                        </ul>
                        <a href="{{ asset('templates/import_jadwal_template.xlsx') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-download me-1"></i> Download Template
                        </a>
                    </div>

                    <form method="POST" action="{{ route('jadwal.import') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label fw-bold">File Jadwal <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror"
                                   id="file" name="file"
                                   accept=".xlsx,.xls,.csv" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-import me-1"></i> Import Jadwal
                            </button>
                            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
