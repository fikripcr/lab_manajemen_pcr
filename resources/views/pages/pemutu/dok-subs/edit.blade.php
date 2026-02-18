@if(request()->ajax() || request()->has('ajax'))
    <x-tabler.form-modal
        title="Edit: {{ $dokSub->judul }}"
        route="{{ route('pemutu.dok-subs.update', $dokSub->doksub_id) }}"
        method="PUT"
        submitText="Simpan Perubahan"
        submitIcon="ti-device-floppy"
    >
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <x-tabler.form-input name="judul" label="Judul" id="judul" value="{{ $dokSub->judul }}" required="true" />
                </div>
            </div>
            <div class="col-md-4">
                <x-tabler.form-input type="number" id="seq" name="seq" label="Urutan" value="{{ $dokSub->seq }}" />
            </div>
        </div>

        @php
            $jenis = strtolower(trim($dokSub->dokumen->jenis));
            $canProduceIndikator = in_array($jenis, ['standar', 'formulir', 'manual_prosedur', 'renop']);
        @endphp

        @if($canProduceIndikator)
        <div class="mb-3">
            <x-tabler.form-checkbox 
                name="is_hasilkan_indikator" 
                label="Hasilkan Indikator {{ ucfirst($jenis === 'renop' ? 'renop' : 'standar') }}?" 
                value="1" 
                :checked="$dokSub->is_hasilkan_indikator" 
                switch 
            />
            <div class="text-muted small">Jika dicentang, poin ini akan memiliki tombol untuk input Indikator di halaman detail.</div>
        </div>
        @endif

        <x-tabler.form-textarea type="editor" name="isi" id="isi" label="Konten / Isi Lengkap" :value="$dokSub->isi" height="400" />
    </x-tabler.form-modal>
@else
    @extends('layouts.admin.app')

    @section('header')
    <x-tabler.page-header title="Edit Isi Dokumen" pretitle="SPMI / Sub Dokumen" />
    @endsection

    @section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit: {{ $dokSub->judul }}</h3>
                    <div class="card-actions">
                        <x-tabler.button href="{{ route('pemutu.dokumens.show', $dokSub->dok_id) }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
                    </div>
                </div>

                <form action="{{ route('pemutu.dok-subs.update', $dokSub->doksub_id) }}" method="POST" class="ajax-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <x-tabler.form-input name="judul" label="Judul" id="judul" value="{{ $dokSub->judul }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <x-tabler.form-input type="number" id="seq" name="seq" label="Urutan" value="{{ $dokSub->seq }}" />
                            </div>
                        </div>

                        @php
                            $jenis = strtolower(trim($dokSub->dokumen->jenis));
                            $canProduceIndikator = in_array($jenis, ['standar', 'formulir', 'manual_prosedur', 'renop']);
                        @endphp

                        @if($canProduceIndikator)
                        <div class="mb-3">
                            <x-tabler.form-checkbox 
                                name="is_hasilkan_indikator" 
                                label="Hasilkan Indikator {{ ucfirst($jenis === 'renop' ? 'renop' : 'standar') }}?" 
                                value="1" 
                                :checked="$dokSub->is_hasilkan_indikator" 
                                switch 
                            />
                            <div class="text-muted small">Jika dicentang, poin ini akan memiliki tombol untuk input Indikator di halaman detail.</div>
                        </div>
                        @endif

                        <x-tabler.form-textarea type="editor" name="isi" id="isi" label="Konten / Isi Lengkap" :value="$dokSub->isi" height="400" />
                    </div>

                    <div class="card-footer text-end">
                        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy" text="Simpan Perubahan" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
@endif
