@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Data Pegawai" pretitle="Dashboard > Pegawai">
    <x-slot:actions>
        <x-tabler.button href="{{ route('hr.pegawai.create') }}" class="btn-success d-none d-sm-inline-block" icon="ti ti-plus" text="Data Baru" />
        <x-tabler.button href="#" class="btn-primary d-none d-sm-inline-block ms-2" icon="ti ti-arrow-right" text="Set Penyelia" />
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
