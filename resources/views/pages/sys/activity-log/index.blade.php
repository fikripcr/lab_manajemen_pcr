@extends('layouts.tabler.app')

@section('title', 'Activity Log')

@section('header')
<x-tabler.page-header title="Activity Log" pretitle="System Log">
    <x-slot:actions>
        <x-tabler.button href="{{ route('sys.dashboard') }}" text="Kembali" icon="ti ti-arrow-left" class="btn-outline-secondary" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('sys.activity-log.*') ? 'active fw-bold' : '' }}" href="{{ route('sys.activity-log.index') }}">
                    <i class="ti ti-activity me-1"></i> Activity Log
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('sys.notifications.*') ? 'active fw-bold' : '' }}" href="{{ route('sys.notifications.index') }}">
                    <i class="ti ti-bell me-1"></i> Notifications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('sys.error-log.*') ? 'active fw-bold' : '' }}" href="{{ route('sys.error-log.index') }}">
                    <i class="ti ti-bug me-1"></i> Error Log
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('app-config') ? 'active fw-bold' : '' }}" href="{{ route('app-config') }}">
                    <i class="ti ti-settings me-1"></i> App Configuration
                </a>
            </li>
        </ul>
    </div>
    <div class="card-header border-bottom-0">
        <div class="d-flex flex-wrap justify-content-between w-100">
            <div class="d-flex flex-wrap gap-2 mb-2 mb-sm-0">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'activity-log-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'activity-log-table'" />
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <x-tabler.datatable
            id="activity-log-table"
            route="{{ route('sys.activity-log.data') }}"
            :columns="[
                [
                    'title' => '#',
                    'data' => 'DT_RowIndex',
                    'name' => 'DT_RowIndex',
                    'orderable' => false,
                    'searchable' => false,
                    'class' => 'text-center'
                ],
                [
                    'title' => 'Time',
                    'data' => 'created_at',
                    'name' => 'created_at'
                ],
                [
                    'title' => 'User',
                    'data' => 'causer_name',
                    'name' => 'causer_name'
                ],
                [
                    'title' => 'Log Name',
                    'data' => 'log_name',
                    'name' => 'log_name'
                ],
                [
                    'title' => 'Description',
                    'data' => 'description',
                    'name' => 'description'
                ],
                [
                    'title' => 'Actions',
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                    'class' => 'text-center'
                ]
            ]"
            :order="[[1, 'desc']]"
        />
    </div>
</div>

{{-- Modal is now loaded via AJAX --}}

<script>
</script>
@endsection
