@extends('layouts.tabler.app')

@section('title', 'Activity Log')

@section('header')
<x-tabler.page-header title="Activity Log" pretitle="System Log" />
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between">
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
            route="{{ route('activity-log.data') }}"
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
