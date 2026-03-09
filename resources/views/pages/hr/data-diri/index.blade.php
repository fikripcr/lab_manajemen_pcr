@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Data Pegawai" pretitle="Dashboard > Pegawai">
    <x-slot:actions>
        <x-tabler.button type="create" href="{{ route('hr.pegawai.create') }}" class="d-none d-sm-inline-block" text="Data Baru" />
        <x-tabler.button href="#" class="btn-primary d-none d-sm-inline-block ms-2" icon="ti ti-arrow-right" text="Set Penyelia" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.card>
    @include('pages.hr.data-diri.global-tab-nav')
    <x-tabler.card-body class="p-0">
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-pegawai">
                @include('pages.hr.data-diri.pegawai')
            </div>
        </div>
    </x-tabler.card-body>
</x-tabler.card>
@endsection
