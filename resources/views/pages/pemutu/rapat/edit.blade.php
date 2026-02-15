@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Ubah Rapat
            </h2>
            <div class="text-muted mt-1">Pemutu / Meeting / Edit</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <x-tabler.button type="back" href="{{ route('pemutu.rapat.index') }}" />
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

                    <form class="ajax-form" action="{{ route('pemutu.rapat.update', $rapat) }}" method="POST">
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
                                    value="{{ old('waktu_mulai', $rapat->waktu_mulai) }}"
                                    required="true" 
                                />
                            </div>
                            <div class="col-md-3">
                                <x-tabler.form-input 
                                    name="waktu_selesai" 
                                    label="Waktu Selesai" 
                                    type="time" 
                                    value="{{ old('waktu_selesai', $rapat->waktu_selesai) }}"
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
                            <div class="col-md-6">
                                <x-tabler.form-select 
                                    name="ketua_user_id" 
                                    label="Ketua Rapat" 
                                    type="select2" 
                                    :options="$users->pluck('name', 'id')->toArray()"
                                    :selected="old('ketua_user_id', $rapat->ketua_user_id)" 
                                    placeholder="Pilih ketua rapat" 
                                />
                            </div>
                            <div class="col-md-6">
                                <x-tabler.form-select 
                                    name="notulen_user_id" 
                                    label="Notulen Rapat" 
                                    type="select2" 
                                    :options="$users->pluck('name', 'id')->toArray()"
                                    :selected="old('notulen_user_id', $rapat->notulen_user_id)" 
                                    placeholder="Pilih notulen rapat" 
                                />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-tabler.form-select 
                                    name="author_user_id" 
                                    label="Author" 
                                    type="select2" 
                                    :options="$users->pluck('name', 'id')->toArray()"
                                    :selected="old('author_user_id', $rapat->author_user_id)" 
                                    placeholder="Pilih author" 
                                />
                            </div>
                            <div class="col-md-6">
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
                            <x-tabler.button type="cancel" href="{{ route('pemutu.rapat.index') }}" />
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
                        <div class="table-responsive">
                            <table class="table table-vcenter table-striped">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Nama Entitas</th>
                                        <th>Keterangan</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rapat->entitas as $entitas)
                                        <tr>
                                            <td>{{ $entitas->model }}</td>
                                            <td>{{ $entitas->model_id }}</td>
                                            <td>{{ $entitas->keterangan ?? '-' }}</td>
                                            <td>
                                                <div class="btn-list">
                                                    <x-tabler.button type="edit" class="btn-sm" href="{{ route('pemutu.rapat.edit', $entitas) }}" text="Edit" />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
