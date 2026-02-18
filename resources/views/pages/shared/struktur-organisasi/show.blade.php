@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Detail Unit: {{ $orgUnit->name }}" pretitle="Detail Data">
    <x-slot:actions>
        <x-tabler.button href="{{ route('shared.struktur-organisasi.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
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
                        <div class="datagrid-title">Nama Unit</div>
                        <div class="datagrid-content">{{ $orgUnit->name }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Kode</div>
                        <div class="datagrid-content">{{ $orgUnit->code }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Tipe</div>
                        <div class="datagrid-content">{{ $orgUnit->type }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Parent Unit</div>
                        <div class="datagrid-content">{{ $orgUnit->parent->name ?? '-' }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Level</div>
                        <div class="datagrid-content">{{ $orgUnit->level }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Status</div>
                        <div class="datagrid-content">
                            @if($orgUnit->is_active)
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
