@extends('layouts.admin.app')

@section('title', 'Buat Request Software')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Buat Request Software
                </h2>
                <div class="text-muted mt-1">
                    Ajukan kebutuhan software untuk mata kuliah
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.software-requests.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('lab.software-requests.store') }}" method="POST" class="ajax-form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Form Pengajuan</h3>

                            @if(isset($error))
                                <div class="alert alert-warning">
                                    <div class="d-flex">
                                        <div>
                                            <i class="ti ti-alert-triangle me-2 fs-2"></i>
                                        </div>
                                        <div>
                                            {{ $error }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($activePeriod)
                                <div class="alert alert-info mb-3">
                                    <strong>Periode Aktif:</strong> {{ $activePeriod->nama_periode }} 
                                    ({{ formatTanggalIndo($activePeriod->start_date) }} - {{ formatTanggalIndo($activePeriod->end_date) }})
                                </div>
                                <input type="hidden" name="periodsoftreq_id" value="{{ $activePeriod->periodsoftreq_id }}">
                            @endif
                            
                            <div class="mb-3">
                                <x-tabler.form-select 
                                    name="mata_kuliah_ids[]" 
                                    label="Mata Kuliah" 
                                    :options="$mataKuliahs->pluck('nama_mk', 'mata_kuliah_id')->toArray()" 
                                    multiple 
                                    required
                                    placeholder="Pilih Mata Kuliah"
                                    class="select2"
                                    help="Pilih satu atau lebih mata kuliah yang membutuhkan software ini."
                                />
                            </div>

                            <x-tabler.form-input name="nama_software" label="Nama Software" placeholder="Misal: Visual Studio Code, MATLAB 2024" required :disabled="!$activePeriod" />

                            <div class="row">
                                <div class="col-md-6">
                                    <x-tabler.form-input name="versi" label="Versi (Opsional)" placeholder="Contoh: v1.8.0" :disabled="!$activePeriod" />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input type="url" name="url_download" label="URL Download (Opsional)" placeholder="https://..." :disabled="!$activePeriod" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Keterangan / Deskripsi</label>
                                <x-tabler.editor name="deskripsi" id="deskripsi-editor" height="300" />
                            </div>

                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary" {{ !$activePeriod ? 'disabled' : '' }}>Kirim Pengajuan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@endsection
