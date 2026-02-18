@extends('layouts.tabler.app')

@section('content')
@if(isset($pegawai))
    @include('components.hr.profile-header')
@endif
<div class="card">
    @include('pages.hr.data-diri.global-tab-nav')
    <div class="card-body p-0">
        <div class="tab-content">
            <div class="tab-pane active show" id="tabs-penugasan">
                @include('pages.hr.data-diri.penugasan')
            </div>
        </div>
    </div>
</div>
@endsection
