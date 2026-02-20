@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Tambah Indikator Standar" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.standar.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <form method="POST" action="{{ route('pemutu.standar.store') }}" class="card ajax-form">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-tabler.form-select name="type" label="Tipe Indikator" required="true">
                            <option value="standar">Standard (Indikator Standar)</option>
                            <option value="performa">Performance (Indikator Performa)</option>
                        </x-tabler.form-select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-tabler.form-select id="dokumen_id" name="dokumen_id_context" label="Dokumen Standar" required="true">
                            <option value="">Pilih Dokumen...</option>
                            @foreach($dokumens as $dok)
                                <option value="{{ $dok->encrypted_dok_id }}">{{ $dok->kode }} - {{ $dok->judul }}</option>
                            @endforeach
                        </x-tabler.form-select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-tabler.form-select name="doksub_id" id="doksub_id" label="Pernyataan Standar (DokSub)" required="true" disabled="true">
                            <option value="">Pilih Dokumen Terlebih Dahulu...</option>
                        </x-tabler.form-select>
                    </div>
                </div>

                <div class="mb-3">
                    <x-tabler.form-textarea name="indikator" label="Isi Indikator" rows="3" required="true" />
                </div>

                <div class="mb-3">
                    <x-tabler.form-input name="target" label="Target" required="true" placeholder="cth: 100%, 5 Dokumen, dsb." />
                </div>
                
                <input type="hidden" name="parent_id" value="">
            </div>

            <div class="card-footer text-end">
                <x-tabler.button type="submit" class="btn-primary" text="Simpan Indikator" />
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dokumenSelect = document.getElementById('dokumen_id');
        const dokSubSelect = document.getElementById('doksub_id');

        dokumenSelect.addEventListener('change', function() {
            const dokId = this.value;
            dokSubSelect.innerHTML = '<option value="">Loading...</option>';
            dokSubSelect.disabled = true;

            if (dokId) {
                fetch(`{{ url('pemutu/dok-subs') }}/${dokId}/data`) // Check if this route returns json list or datatable
                // The route 'dok-subs.data' points to DokSubController@data which likely returns Datatables.
                // We need a simple API. Let's use the method we added to IndikatorStandarController@getDokSubs
                
                fetch(`{{ url('pemutu/standar/get-dok-subs') }}/${dokId}`)
                    .then(response => response.json())
                    .then(data => {
                        dokSubSelect.innerHTML = '<option value="">Select Sub-Document...</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.encrypted_doksub_id;
                            option.textContent = item.judul.substring(0, 100) + (item.judul.length > 100 ? '...' : '');
                            dokSubSelect.appendChild(option);
                        });
                        dokSubSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        dokSubSelect.innerHTML = '<option value="">Error fetching data</option>';
                    });
            } else {
                dokSubSelect.innerHTML = '<option value="">Select Document First...</option>';
                dokSubSelect.disabled = true;
            }
        });
    });
</script>
@endpush
@endsection
