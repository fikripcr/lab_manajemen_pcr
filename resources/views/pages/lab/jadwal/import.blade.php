@extends('layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Import Jadwal" pretitle="Jadwal Kuliah">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('jadwal.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-info-circle fs-2 me-2"></i>
                            </div>
                            <div>
                                <h4 class="alert-title">Petunjuk Import Jadwal</h4>
                                <div class="text-muted">
                                    Gunakan format file Excel (.xlsx) dengan struktur kolom sebagai berikut:
                                    <ul class="mt-2 mb-2">
                                        <li><strong>tahun_ajaran</strong> - Format: YYYY/YYYY (contoh: 2023/2024)</li>
                                        <li><strong>semester</strong> - 1 (Ganjil) atau 2 (Genap)</li>
                                        <li><strong>kode_mk</strong> - Kode mata kuliah (contoh: IF101)</li>
                                        <li><strong>dosen</strong> - Nama atau email dosen</li>
                                        <li><strong>hari</strong> - Hari perkuliahan (contoh: Senin, Selasa)</li>
                                        <li><strong>jam_mulai</strong> - Format: HH:MM (contoh: 08:00)</li>
                                        <li><strong>jam_selesai</strong> - Format: HH:MM (contoh: 10:00)</li>
                                        <li><strong>lab</strong> - Nama lab (contoh: Lab Jaringan)</li>
                                    </ul>
                                    <a href="{{ Vite::asset('resources/assets/templates/template_import_jadwal.xlsx') }}" class="btn btn-sm btn-outline-info">
                                        <i class="ti ti-download me-1"></i> Download Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('jadwal.import') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="file" class="col-sm-2 col-form-label required">File Jadwal</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control @error('file') is-invalid @enderror"
                                       id="file" name="file"
                                       accept=".xlsx,.xls,.csv" required>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Import Jadwal" icon="ti ti-upload" />
                                <x-tabler.button type="cancel" :href="route('jadwal.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
