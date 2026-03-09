@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Detail Pegawai: {{ $pegawai->nama }}" pretitle="Detail Data">
    <x-slot:actions>
        <x-tabler.button href="{{ route('shared.pegawai.index') }}" type="back" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <x-tabler.card>
            <x-tabler.card-body>
                <div class="datagrid">
                    <div class="datagrid-item">
                        <div class="datagrid-title">NIP</div>
                        <div class="datagrid-content">{{ $pegawai->nip }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Nama</div>
                        <div class="datagrid-content">{{ $pegawai->nama }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Email</div>
                        <div class="datagrid-content">{{ $pegawai->email }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Unit Kerja</div>
                        <div class="datagrid-content">{{ $pegawai->unitKerja->name ?? '-' }}</div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
@endsection
