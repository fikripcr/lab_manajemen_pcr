@extends('layouts.tabler.app')

@section('title', 'Pengaturan Presensi')
@section('pretitle', 'Konfigurasi lokasi kantor and radius presensi online')

@section('actions')
    <x-tabler.button href="{{ route('hr.presensi.index') }}" class="btn-ghost-secondary" icon="ti ti-arrow-left" text="Kembali" />
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Konfigurasi Lokasi Kantor</h3>
            </div>
            <div class="card-body">
                <form action="#" method="POST" class="ajax-form">
                    @csrf
                    <div class="mb-3">
                        <x-tabler.form-input name="office_name" label="Nama Kantor" value="Politeknik Caltex Riau" />
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-tabler.form-input name="latitude" label="Latitude" value="-0.531234" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-tabler.form-input name="longitude" label="Longitude" value="101.442345" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <x-tabler.form-input name="radius" label="Radius (Meter)" type="number" value="500" />
                        <small class="text-muted">Jarak maksimal karyawan dari koordinat kantor untuk dapat melakukan presensi.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status Fitur Presensi Online</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked>
                            <span class="form-check-label">Aktif</span>
                        </div>
                    </div>
                    
                    <div class="form-footer">
                        <x-tabler.button type="submit" text="Simpan Pengaturan" class="w-100" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Test Akurasi Lokasi</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Gunakan fitur ini untuk mengetes apakah koordinat kantor sudah sesuai dengan lokasi fisik saat ini.</p>
                
                <div class="p-3 border rounded bg-light mb-3">
                    <div class="id-info mb-2">
                        <small class="text-muted uppercase">Lokasi Saat Ini</small>
                        <div id="test-current-coord" class="fw-bold">-</div>
                    </div>
                    <div class="distance-info">
                        <small class="text-muted uppercase">Jarak ke Koordinat Kantor</small>
                        <div id="test-result-distance" class="fw-bold">-</div>
                    </div>
                </div>
                
                <x-tabler.button id="btn-test-location" class="btn-outline-primary w-100" icon="ti ti-map-pin" text="Ambil Lokasi Sekarang & Hitung Jarak" />
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#btn-test-location').on('click', function() {
        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mendapatkan lokasi...');
        
        setTimeout(() => {
            $('#test-current-coord').html('-0.531200, 101.442300');
            $('#test-result-distance').html('38 meter <span class="badge bg-success ms-2">Akurat</span>');
            $(this).prop('disabled', false).html('<i class="ti ti-map-pin me-2"></i>Ambil Lokasi Sekarang & Hitung Jarak');
        }, 2000);
    });
});
</script>
@endsection
