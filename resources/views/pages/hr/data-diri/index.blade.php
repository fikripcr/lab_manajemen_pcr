@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Data Pegawai" pretitle="Dashboard > Pegawai">
    <x-slot:actions>
        <a href="{{ route('hr.pegawai.create') }}" class="btn btn-success d-none d-sm-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Data Baru
        </a>
        <a href="#" class="btn btn-primary d-none d-sm-inline-block ms-2">
            Set Penyelia &rarr;
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    @include('pages.hr.data-diri.global-tab-nav')
    <div class="card-body p-0">
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-pegawai">
                @include('pages.hr.data-diri.pegawai')
            </div>
        </div>
    </div>
</div>
@endsection
