@extends('layouts.tabler.app')

@section('title', $project->exists ? 'Edit Project' : 'Create Project')
@section('pretitle', 'Project Management')

@section('header')
<x-tabler.page-header :title="$project->exists ? 'Edit Project' : 'Create New Project'" pretitle="Projects">
    <x-slot:actions>
        <a href="javascript:void(0)" onclick="history.back()" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Back
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ $project->exists ? route('projects.update', $project) : route('projects.store') }}" method="POST">
            @csrf
            @if($project->exists)
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <x-tabler.form-input
                        name="project_name"
                        label="Project Name"
                        :value="old('project_name', $project->project_name)"
                        placeholder="Enter project name"
                        required="true"
                    />

                    <x-tabler.form-textarea
                        name="project_desc"
                        label="Description"
                        rows="5"
                        :value="old('project_desc', $project->project_desc)"
                        placeholder="Project description..."
                    />
                </div>

                <div class="col-lg-4">
                    <x-tabler.form-select
                        name="status"
                        label="Status"
                        :options="[
                            'planning' => 'Planning',
                            'active' => 'Active',
                            'completed' => 'Completed',
                            'on_hold' => 'On Hold'
                        ]"
                        :selected="old('status', $project->status ?? 'planning')"
                        required="true"
                    />

                    <div class="mb-3">
                        <label class="form-label">Agile Mode</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="is_agile" value="1" class="form-selectgroup-input" {{ old('is_agile', $project->is_agile ?? false) ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">Enabled</span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="is_agile" value="0" class="form-selectgroup-input" {{ !old('is_agile', $project->is_agile ?? false) ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">Disabled</span>
                            </label>
                        </div>
                    </div>

                    <x-tabler.form-input
                        name="start_date"
                        type="date"
                        label="Start Date"
                        :value="old('start_date', $project->start_date?->format('Y-m-d'))"
                        required="true"
                    />

                    <x-tabler.form-input
                        name="end_date"
                        type="date"
                        label="End Date"
                        :value="old('end_date', $project->end_date?->format('Y-m-d'))"
                        required="true"
                    />
                </div>
            </div>

            <div class="mt-4">
                <x-tabler.button
                    type="submit"
                    class="btn-primary"
                    icon="ti ti-device-floppy"
                    text="{{ $project->exists ? 'Update Project' : 'Create Project' }}"
                />
            </div>
        </form>
    </div>
</div>
@endsection
