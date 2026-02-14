@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Agenda Rapat
            </h2>
            <div class="text-muted mt-1">Pemutu / Meeting / Agenda</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('pemutu.rapat.show', $rapat) }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Agenda
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Agenda</h3>
                </div>
                <div class="card-body">
                    <x-tabler.flash-message />

                    <x-tabler.datatable
                        id="agenda-table" route="{{ route('pemutu.rapat.agenda.data', $rapat) }}" :columns="[
                        [
                            'title' => 'Judul Agenda',
                            'data' => 'judul_agenda',
                            'name' => 'judul_agenda',
                        ],
                        [
                            'title' => 'Urutan',
                            'data' => 'seq',
                            'name' => 'seq',
                        ],
                        [
                            'title' => 'Actions',
                            'data' => 'action',
                            'name' => 'action',
                            'orderable' => false,
                            'searchable' => false,
                        ],
                    ]" />
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Agenda</h3>
                </div>
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form class="ajax-form" action="{{ route('pemutu.rapat.agenda.store', $rapat) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-12">
                                <x-tabler.form-input 
                                    name="judul_agenda" 
                                    label="Judul Agenda" 
                                    type="text" 
                                    value="{{ old('judul_agenda') }}"
                                    placeholder="Masukkan judul agenda" 
                                    required="true" 
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <x-tabler.form-textarea 
                                    name="isi" 
                                    label="Isi Agenda" 
                                    value="{{ old('isi') }}"
                                    placeholder="Masukkan isi agenda" 
                                    rows="4" 
                                    required="true" 
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <x-tabler.form-input 
                                    name="seq" 
                                    label="Urutan" 
                                    type="number" 
                                    value="{{ old('seq') }}"
                                    placeholder="Masukkan urutan" 
                                    required="true" 
                                    min="1"
                                />
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('pemutu.rapat.show', $rapat) }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>
                                Batal
                            </a>
                            <x-tabler.button type="submit" text="Simpan Agenda" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
