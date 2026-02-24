@extends('layouts.tabler.app')

@section('title', 'Projects')
@section('pretitle', 'Project Management')

@section('header')
<x-tabler.page-header title="Projects" pretitle="Dashboard">
    <x-slot:actions>
        <x-tabler.button 
            href="{{ route('projects.create') }}" 
            class="btn-primary" 
            icon="ti ti-plus" 
            text="New Project" 
        />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'projects-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'projects-table'" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <x-tabler.flash-message />
        
        @php
            $columns = [
                [
                    'title' => '#',
                    'data' => 'DT_RowIndex',
                    'name' => 'DT_RowIndex',
                    'orderable' => false,
                    'searchable' => false,
                    'class' => 'text-center'
                ],
                [
                    'title' => 'Project Name',
                    'data' => 'project_name',
                    'name' => 'project_name',
                    'orderable' => true,
                    'searchable' => true
                ],
                [
                    'title' => 'Status',
                    'data' => 'status',
                    'name' => 'status',
                    'orderable' => true,
                    'searchable' => false
                ],
                [
                    'title' => 'Start Date',
                    'data' => 'start_date',
                    'name' => 'start_date',
                    'orderable' => true,
                    'searchable' => false
                ],
                [
                    'title' => 'End Date',
                    'data' => 'end_date',
                    'name' => 'end_date',
                    'orderable' => true,
                    'searchable' => false
                ],
                [
                    'title' => 'Progress',
                    'data' => 'progress',
                    'name' => 'progress',
                    'orderable' => false,
                    'searchable' => false
                ],
                [
                    'title' => 'Team',
                    'data' => 'team_size',
                    'name' => 'team_size',
                    'orderable' => true,
                    'searchable' => false,
                    'class' => 'text-center'
                ],
                [
                    'title' => 'Actions',
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                    'class' => 'text-center'
                ]
            ];
        @endphp
        
        <x-tabler.datatable 
            id="projects-table" 
            :route="route('projects.data')" 
            :columns="$columns" 
            :order="[[2, 'desc']]" 
        />
    </div>
</div>
@endsection
