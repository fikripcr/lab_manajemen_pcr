@extends('layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="Agenda Rapat" pretitle="Kegiatan / Meeting / Agenda">
        <x-slot:actions>
            <x-tabler.button type="create" href="{{ route('Kegiatan.rapat.show', $rapat) }}" class="d-none d-sm-inline-block" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <x-tabler.card>
                <x-tabler.card-header title="Daftar Agenda" />
                <x-tabler.card-body>
                    <x-tabler.datatable
                        id="agenda-table" route="{{ route('Kegiatan.rapat.agenda.data', $rapat) }}" :columns="[
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
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
        <div class="col-md-4">
            <x-tabler.card>
                <x-tabler.card-header title="Tambah Agenda" />
                <x-tabler.card-body>
                    <form class="ajax-form" action="{{ route('Kegiatan.rapat.agenda.store', $rapat) }}" method="POST">
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
                            <x-tabler.button :href="route('Kegiatan.rapat.show', $rapat)" type="back" text="Batal" />
                            <x-tabler.button type="submit" text="Simpan Agenda" />
                        </div>
                    </form>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    </div>
@endsection
