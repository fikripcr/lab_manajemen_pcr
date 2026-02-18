@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Detail Personil: {{ $personil->nama }}" pretitle="Detail Data">
    <x-slot:actions>
        <x-tabler.button href="{{ route('shared.personil.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <div class="datagrid">
                    <div class="datagrid-item">
                        <div class="datagrid-title">Nama</div>
                        <div class="datagrid-content">{{ $personil->nama }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Email</div>
                        <div class="datagrid-content">{{ $personil->email }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Posisi</div>
                        <div class="datagrid-content">{{ $personil->posisi }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Vendor</div>
                        <div class="datagrid-content">{{ $personil->vendor }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Unit Kerja</div>
                        <div class="datagrid-content">{{ $personil->unitKerja->name ?? '-' }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Phone</div>
                        <div class="datagrid-content">{{ $personil->phone ?? '-' }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Status</div>
                        <div class="datagrid-content">
                            @if($personil->status_aktif)
                            <span class="badge bg-success text-white">Aktif</span>
                            @else
                            <span class="badge bg-danger text-white">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
