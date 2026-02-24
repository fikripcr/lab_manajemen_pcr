@extends('layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="Detail Personil" pretitle="Master Data / Personil / Detail">
        <x-slot:actions>
            <x-tabler.button type="edit" href="{{ route('lab.personil.edit', encryptId($personil->personil_id)) }}" />
            <x-tabler.button type="back" href="{{ route('lab.personil.index') }}" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($personil->user && $personil->user->avatar_url)
                        <span class="avatar avatar-xl mb-3" style="background-image: url('{{ $personil->user->avatar_url }}')"></span>
                    @else
                        <span class="avatar avatar-xl mb-3 bg-primary-lt">{{ substr($personil->nama, 0, 2) }}</span>
                    @endif
                    <h3 class="m-0">{{ $personil->nama }}</h3>
                    <div class="text-muted">{{ $personil->posisi }}</div>
                    @if($personil->jenis_personil)
                        <div class="mt-2">
                            <span class="badge bg-blue">{{ $personil->jenis_personil }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Personil</h3>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">NIP/NIK</div>
                            <div class="datagrid-content">{{ $personil->nip }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Nama Lengkap</div>
                            <div class="datagrid-content">{{ $personil->nama }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Email</div>
                            <div class="datagrid-content">
                                @if($personil->email)
                                    <a href="mailto:{{ $personil->email }}">{{ $personil->email }}</a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Posisi</div>
                            <div class="datagrid-content">{{ $personil->posisi ?? '-' }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Jenis Personil</div>
                            <div class="datagrid-content">
                                @if($personil->jenis_personil)
                                    <span class="badge bg-blue">{{ $personil->jenis_personil }}</span>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        @if($personil->user)
                        <div class="datagrid-item">
                            <div class="datagrid-title">User Account</div>
                            <div class="datagrid-content">
                                <span class="badge bg-green">{{ $personil->user->name }}</span>
                            </div>
                        </div>
                        @endif
                        <div class="datagrid-item">
                            <div class="datagrid-title">Dibuat</div>
                            <div class="datagrid-content">{{ formatTanggalIndo($personil->created_at) }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Diperbarui</div>
                            <div class="datagrid-content">{{ formatTanggalIndo($personil->updated_at) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
