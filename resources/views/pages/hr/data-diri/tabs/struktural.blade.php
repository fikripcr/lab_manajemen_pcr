@extends('layouts.tabler.app')
@section('header')
@if(isset($pegawai))
    @include('components.hr.profile-header')
@else
    <x-tabler.page-header title="Struktural (Struktural & Unit)" pretitle="HR Management" />
@endif
@endsection

@section('content')
<div class="card">
    @include('pages.hr.data-diri.global-tab-nav')
    <div class="card-body p-0">
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-struktural">
                @include('pages.hr.data-diri.struktural')
            </div>
        </div>
    </div>
</div>
@endsection
