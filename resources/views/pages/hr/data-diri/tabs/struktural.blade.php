@extends('layouts.tabler.app')
@section('header')
@if(isset($pegawai))
    @include('components.hr.profile-header')
@else
    <x-tabler.page-header title="Struktural (Struktural & Unit)" pretitle="HR Management" />
@endif
@endsection

@section('content')
<x-tabler.card>
    @include('pages.hr.data-diri.global-tab-nav')
    <x-tabler.card-body class="p-0">
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-struktural">
                @include('pages.hr.data-diri.struktural')
            </div>
        </div>
    </x-tabler.card-body>
</x-tabler.card>
@endsection
