@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Data Pegawai" pretitle="HR Management" />
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
