@extends('layouts.tabler.app')

@section('header')
@if(isset($pegawai))
    @include('components.hr.profile-header')
@else
    <x-tabler.page-header title="Data Pengembangan Diri" pretitle="HR Management" />
@endif
@endsection

@section('content')
<div class="card">
    @include('pages.hr.data-diri.global-tab-nav')
    <div class="card-body p-0">
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-pengembangan-diri">
                @if(isset($pegawai))
                    @include('pages.hr.pegawai.parts._pengembangan_list')
                @else
                    @include('pages.hr.data-diri.pengembangan')
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
