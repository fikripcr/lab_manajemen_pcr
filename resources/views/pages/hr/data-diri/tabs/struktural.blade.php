@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Riwayat Penugasan & Struktural" pretitle="HR Management" />
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
