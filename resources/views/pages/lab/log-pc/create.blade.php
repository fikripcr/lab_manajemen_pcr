@extends('layouts.admin.app')

@section('title', 'Isi Log Penggunaan PC')

@section('content')
<div class="container-xl">
    <x-tabler.page-header title="Isi Log Penggunaan PC" pretitle="Monitoring" />

    <div class="page-body">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                @if(!$activeJadwal)
                    <div class="alert alert-warning" role="alert">
                        <div class="d-flex">
                            <div>
                                <i class="bx bx-error me-2 h2"></i>
                            </div>
                            <div>
                                <h4 class="alert-title">Tidak ada jadwal aktif!</h4>
                                <div class="text-secondary">
                                    Saat ini tidak ada jadwal perkuliahan yang aktif untuk Anda isi log-nya. 
                                    Silahkan coba lagi nanti sesuai jadwal.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <form action="{{ route('lab.log-pc.store') }}" method="POST" class="ajax-form">
                        @csrf
                        <div class="card">
                            <div class="card-status-top bg-primary"></div>
                            <div class="card-body">
                                <h3 class="card-title">Informasi Perkuliahan</h3>
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Mata Kuliah</div>
                                        <div class="datagrid-content">{{ $activeJadwal->mataKuliah->nama_mk }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Dosen</div>
                                        <div class="datagrid-content">{{ $activeJadwal->dosen->name }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Lab</div>
                                        <div class="datagrid-content">{{ $activeJadwal->lab->name }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Assignment PC Anda</div>
                                        <div class="datagrid-content">
                                            @if($assignment)
                                                <span class="badge bg-blue text-blue-fg">PC {{ $assignment->nomor_pc }}</span>
                                            @else
                                                <span class="badge bg-warning text-warning-fg">Belum ada assignment</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="hr-text">Input Kondisi</div>

                                @if(!$assignment)
                                    <div class="alert alert-danger">
                                        Anda belum ditugaskan ke PC manapun di jadwal ini. Hubungi Dosen/Laboran.
                                        (Anda tidak dapat mengisi log tanpa assignment).
                                    </div>
                                @else
                                    <input type="hidden" name="jadwal_id" value="{{ encryptId($activeJadwal->jadwal_kuliah_id) }}">
                                    <input type="hidden" name="lab_id" value="{{ encryptId($activeJadwal->lab_id) }}">
                                    
                                    <div class="mb-3">
                                        <label class="form-label required">Kondisi PC Saat Ini</label>
                                        <div>
                                            <x-tabler.form-radio 
                                                name="status_pc" 
                                                label="Baik" 
                                                value="Baik" 
                                                checked 
                                                class="form-check-inline" 
                                            />
                                            <x-tabler.form-radio 
                                                name="status_pc" 
                                                label="Rusak / Bermasalah" 
                                                value="Rusak" 
                                                class="form-check-inline" 
                                            />
                                        </div>
                                    </div>

                                    <x-tabler.form-textarea name="catatan_umum" label="Catatan (Opsional)" rows="3" placeholder="Contoh: Mouse agak macet, Keyboard tombol A keras..." />

                                    <div class="form-footer">
                                        <x-tabler.button type="submit" text="Simpan Log" class="w-100" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
