@extends('layouts.tabler.app')

@section('header')
@if(isset($pegawai))
    @include('components.hr.profile-header')
@else
    <x-tabler.page-header title="Status Aktifitas" pretitle="HR Management" />
@endif
@endsection

@section('content')
<x-tabler.card>
    @include('pages.hr.data-diri.global-tab-nav')
    <x-tabler.card-body class="p-0">
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-status-aktifitas">
                @if(isset($pegawai))
                    @include('pages.hr.pegawai.parts._status_aktifitas_list')
                @else
                    @include('pages.hr.data-diri.status-aktifitas')
                @endif
            </div>
        </div>
    </x-tabler.card-body>
</x-tabler.card>
@endsection
