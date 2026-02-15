@extends('layouts.admin.app')

@section('title', 'Buat Request Software')

@section('content')
<div class="container-xl">
    <x-tabler.page-header title="Buat Request Software" pretitle="Berkas">
        <x-slot:actions>
            <x-tabler.button type="back" href="{{ route('lab.software-requests.index') }}" />
        </x-slot:actions>
    </x-tabler.page-header>

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

                            <x-tabler.form-textarea type="editor" name="deskripsi" id="deskripsi-editor" label="Keterangan / Deskripsi" required="true" height="300" />

                        </div>
                        <div class="card-footer text-end">
                            <x-tabler.button type="cancel" href="{{ route('lab.software-requests.index') }}" />
                            <x-tabler.button type="submit" class="btn-primary" icon="bx bx-send" text="Kirim Pengajuan" :disabled="!$activePeriod" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@endsection
