@extends('layouts.tabler.app')

@section('header')
@if(isset($pegawai))
    @include('components.hr.profile-header')
@else
    <x-tabler.page-header title="Data Jabatan Fungsional" pretitle="HR Management" />
@endif
@endsection

@section('content')
<div class="card">
    @include('pages.hr.data-diri.global-tab-nav')
    <div class="card-body p-0">
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-fungsional">
                @if(isset($pegawai))
                    @include('pages.hr.pegawai.parts._jabatan_fungsional_list')
                @else
                    @include('pages.hr.data-diri.fungsional')
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
