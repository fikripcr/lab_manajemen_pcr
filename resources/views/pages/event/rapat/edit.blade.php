@extends('layouts.tabler.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Ubah Rapat
            </h2>
            <div class="text-muted mt-1">Kegiatan / Meeting / Edit</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <x-tabler.button type="back" href="{{ route('Kegiatan.rapat.index') }}" />
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ubah Data Rapat</h3>
                </div>
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form class="ajax-form" action="{{ route('Kegiatan.rapat.update', $rapat->encrypted_rapat_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <x-tabler.form-input 
                                    name="jenis_rapat" 
                                    label="Jenis Rapat" 
                                    type="text" 
                                    value="{{ old('jenis_rapat', $rapat->jenis_rapat) }}"
                                    placeholder="Internal, Eksternal, dll" 
                                    required="true" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-tabler.form-input 
                                    name="judul_kegiatan" 
                                    label="Judul Kegiatan" 
                                    type="text" 
                                    value="{{ old('judul_kegiatan', $rapat->judul_kegiatan) }}"
                                    placeholder="Masukkan judul kegiatan" 
                                    required="true" 
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-tabler.form-input 
                                    name="tgl_rapat" 
                                    label="Tanggal Rapat" 
                                    type="date" 
                                    value="{{ old('tgl_rapat', $rapat->tgl_rapat) }}"
                                    required="true" 
                                />
                            </div>
                            <div class="col-md-3">
                                <x-tabler.form-input 
                                    name="waktu_mulai" 
                                    label="Waktu Mulai" 
                                    type="time" 
                                    value="{{ old('waktu_mulai', $rapat->waktu_mulai?->format('H:i')) }}"
                                    required="true" 
                                />
                            </div>
                            <div class="col-md-3">
                                <x-tabler.form-input 
                                    name="waktu_selesai" 
                                    label="Waktu Selesai" 
                                    type="time" 
                                    value="{{ old('waktu_selesai', $rapat->waktu_selesai?->format('H:i')) }}"
                                    required="true" 
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-tabler.form-input 
                                    name="tempat_rapat" 
                                    label="Tempat Rapat" 
                                    type="text" 
                                    value="{{ old('tempat_rapat', $rapat->tempat_rapat) }}"
                                    placeholder="Ruang Meeting, Zoom, dll" 
                                    required="true" 
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <x-tabler.form-textarea 
                                    name="keterangan" 
                                    label="Keterangan" 
                                    value="{{ old('keterangan', $rapat->keterangan) }}"
                                    placeholder="Masukkan keterangan tambahan" 
                                    rows="3" 
                                />
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <x-tabler.button type="cancel" href="{{ route('Kegiatan.rapat.index') }}" />
                            <x-tabler.button type="submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Entitas Terkait</h3>
                </div>
                <div class="card-body">
                    @if($rapat->entitas->count() > 0)
                        <x-tabler.datatable-client
                            id="table-entitas"
                            :columns="[
                                ['name' => 'Model'],
                                ['name' => 'Nama Entitas'],
                                ['name' => 'Keterangan'],
                                ['name' => 'Actions', 'orderable' => false, 'searchable' => false]
                            ]"
                        >
                            @foreach($rapat->entitas as $entitas)
                                <tr>
                                    <td>{{ $entitas->model }}</td>
                                    <td>{{ $entitas->model_id }}</td>
                                    <td>{{ $entitas->keterangan ?? '-' }}</td>
                                    <td>
                                        <div class="btn-list">
                                            <x-tabler.button type="edit" class="btn-sm" href="{{ route('Kegiatan.rapat.edit', $entitas->encrypted_rapatentitas_id) }}" text="Edit" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </x-tabler.datatable-client>
                    @else
                        <div class="alert alert-info">
                            <strong>Belum ada entitas terkait</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
