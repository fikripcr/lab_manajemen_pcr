@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Detail Rapat
            </h2>
            <div class="text-muted mt-1">Pemutu / Meeting / Detail</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('pemutu.rapat.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
                    <i class="ti ti-arrow-left me-1"></i>
                    Kembali
                </a>
                <a href="{{ route('pemutu.rapat.edit', $rapat) }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-edit me-1"></i>
                    Edit
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
                    <h3 class="card-title">Informasi Rapat</h3>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Jenis Rapat</div>
                            <div class="datagrid-content">{{ $rapat->jenis_rapat }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Judul Kegiatan</div>
                            <div class="datagrid-content">{{ $rapat->judul_kegiatan }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Tanggal Rapat</div>
                            <div class="datagrid-content">{{ formatTanggalIndo($rapat->tgl_rapat) }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Waktu</div>
                            <div class="datagrid-content">
                                {{ $rapat->waktu_mulai->format('H:i') }} - {{ $rapat->waktu_selesai->format('H:i') }}
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Tempat</div>
                            <div class="datagrid-content">{{ $rapat->tempat_rapat }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Ketua Rapat</div>
                            <div class="datagrid-content">
                                @if($rapat->ketuaUser)
                                    {{ $rapat->ketuaUser->name }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Notulen Rapat</div>
                            <div class="datagrid-content">
                                @if($rapat->notulenUser)
                                    {{ $rapat->notulenUser->name }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Author</div>
                            <div class="datagrid-content">
                                @if($rapat->authorUser)
                                    {{ $rapat->authorUser->name }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Dibuat</div>
                            <div class="datagrid-content">{{ formatTanggalIndo($rapat->created_at) }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Diperbarui</div>
                            <div class="datagrid-content">{{ formatTanggalIndo($rapat->updated_at) }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Keterangan</div>
                            <div class="datagrid-content">{{ $rapat->keterangan ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
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
                                                    <a href="{{ route('pemutu.rapat.edit', $entitas) }}" class="btn btn-sm btn-primary">
                                                        <i class="ti ti-edit me-1"></i>
                                                        Edit
                                                    </a>
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
