@extends('layouts.tabler.app')

@section('header')
@if(isset($pegawai))
    @include('components.hr.profile-header')
@else
    <x-tabler.page-header title="Data Keluarga" pretitle="HR Management" />
@endif
@endsection

@section('content')
<x-tabler.card>
    @include('pages.hr.data-diri.global-tab-nav')
    <x-tabler.card-body class="p-0">
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-keluarga">
                @if(isset($pegawai))
                    @include('pages.hr.pegawai.parts._keluarga_list')
                @else
                    @include('pages.hr.data-diri.keluarga')
                @endif
            </div>
        </div>
    </x-tabler.card-body>
</x-tabler.card>
@endsection
