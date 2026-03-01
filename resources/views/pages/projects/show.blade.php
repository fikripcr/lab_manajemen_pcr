@extends('layouts.tabler.app')

@section('title', $project->project_name)
@section('pretitle', 'Project Dashboard')

@section('header')
<x-tabler.page-header :title="$project->project_name" pretitle="Overview">
    <x-slot:actions>
        <div class="btn-list">
            <x-tabler.button 
                href="javascript:history.back()" 
                class="btn-outline-secondary" 
                icon="ti ti-arrow-left" 
                text="Kembali" 
            />
            <x-tabler.button 
                href="{{ route('projects.edit', $project) }}" 
                class="btn-primary" 
                icon="ti ti-edit" 
                text="Edit Project" 
            />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.flash-message />

{{-- Project Info Cards --}}
<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Tasks</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">{{ $statistics['total_tasks'] }}</div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-primary" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Completed</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">{{ $statistics['completed_tasks'] }}</div>
                    <div class="ms-auto text-muted">
                        {{ number_format($statistics['progress_percentage'], 1) }}%
                    </div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-success" style="width: {{ $statistics['progress_percentage'] }}%"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">In Progress</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">{{ $statistics['in_progress_tasks'] }}</div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-warning" style="width: {{ $statistics['total_tasks'] > 0 ? ($statistics['in_progress_tasks'] / $statistics['total_tasks'] * 100) : 0 }}%"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Cost</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">Rp {{ number_format($statistics['total_cost'], 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-success" style="width: 100%"></div>
            </div>
        </div>
    </div>
</div>

{{-- Segmented Control Tabs --}}
<div class="card">
    <div class="card-header">
        <ul class="nav nav-pills card-header-pills" role="tablist" id="projectTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#project-overview" role="tab" data-tab-id="overview">
                    <i class="ti ti-layout-dashboard me-2"></i>Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#project-tasks" role="tab" data-tab-id="tasks">
                    <i class="ti ti-checklist me-2"></i>Tasks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#project-team" role="tab" data-tab-id="team">
                    <i class="ti ti-users me-2"></i>Team
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#project-cost" role="tab" data-tab-id="cost">
                    <i class="ti ti-cash me-2"></i>Cost
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            
            {{-- Overview Tab --}}
            <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <h4 class="mb-3">Project Information</h4>
                        <div class="mb-3">
                            <label class="form-label text-muted">Project Name</label>
                            <div class="h4">{{ $project->project_name }}</div>
                        </div>

                        <div class="row row-cards">
                            @if($project->project_desc)
                            <div class="col-md-12 mb-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-primary text-white avatar"><i class="ti ti-file-description"></i></span>
                                            <div class="ms-3">
                                                <div class="font-weight-medium">Description</div>
                                                <div class="text-muted small mt-1">{!! nl2br(e($project->project_desc ?: 'No description provided.')) !!}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="col-md-6 mb-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-info text-white avatar"><i class="ti ti-calendar"></i></span>
                                            <div class="ms-3">
                                                <div class="font-weight-medium">Timeline</div>
                                                <div class="text-muted small mt-1">
                                                    Start: {{ formatTanggalIndo($project->start_date) }}<br>
                                                    End: {{ formatTanggalIndo($project->end_date) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-{{ $project->status_badge_class }} text-white avatar"><i class="ti ti-activity"></i></span>
                                            <div class="ms-3">
                                                <div class="font-weight-medium">Status</div>
                                                <div class="text-muted small mt-1">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-{{ $project->is_agile ? 'success' : 'secondary' }} text-white avatar"><i class="ti ti-refresh"></i></span>
                                            <div class="ms-3">
                                                <div class="font-weight-medium">Agile Mode</div>
                                                <div class="text-muted small mt-1">{{ $project->is_agile ? 'Enabled' : 'Disabled' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($project->is_agile)
                            <div class="col-md-6 mb-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-purple text-white avatar"><i class="ti ti-layers-intersect"></i></span>
                                            <div class="ms-3">
                                                <div class="font-weight-medium">Phase & Sprint</div>
                                                <div class="text-muted small mt-1">
                                                    {{ $project->phases->count() }} Phases, {{ $project->sprints->count() }} Sprints
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header border-0 pb-0">
                                <h3 class="card-title">Quick Stats</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Task Completion</span>
                                        <span class="text-muted">{{ $statistics['completed_tasks'] }}/{{ $statistics['total_tasks'] }}</span>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: {{ $statistics['progress_percentage'] }}%"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">To Do</span>
                                        <span class="badge bg-blue-lt">{{ $statistics['todo_tasks'] ?? 0 }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">In Progress</span>
                                        <span class="badge bg-yellow-lt">{{ $statistics['in_progress_tasks'] ?? 0 }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Done</span>
                                        <span class="badge bg-green-lt">{{ $statistics['completed_tasks'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tasks Tab (jKanban) --}}
            <div class="tab-pane fade" id="project-tasks" role="tabpanel">
                <div id="taskKanban"></div>
            </div>

            {{-- Team Tab --}}
            <div class="tab-pane fade" id="project-team" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Project Team</h4>
                    <x-tabler.button 
                        href="javascript:void(0)" 
                        class="btn-primary btn-sm ajax-modal-btn" 
                        data-url="{{ route('projects.members.create-modal', $project) }}"
                        data-modal-title="Add Team Member"
                        icon="ti ti-plus" 
                        text="Add Member" 
                    />
                </div>
                @if($project->members->count() > 0)
                <div class="row row-cards">
                    @foreach($project->members as $member)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md me-3">
                                        {{ substr(($member->user->name ?? 'U'), 0, 1) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $member->user->name ?? 'Unknown' }}</div>
                                        <div class="text-muted small">
                                            @if($member->alias_position)
                                                {{ $member->alias_position }}
                                            @else
                                                {{ ucfirst($member->role) }}
                                            @endif
                                        </div>
                                    </div>
                                    <span class="badge {{ $member->role_badge_class }} me-2">
                                        {{ ucfirst($member->role) }}
                                    </span>
                                    <div class="dropdown">
                                        <x-tabler.button type="button" class="btn-icon shadow-none text-muted border-0" data-bs-toggle="dropdown" icon="ti ti-dots-vertical" iconOnly="true" />
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item text-danger ajax-delete" 
                                               href="javascript:void(0)" 
                                               data-url="{{ route('projects.members.destroy', [$project, $member]) }}"
                                               data-title="Remove member: {{ $member->user->name }}?">
                                                <i class="ti ti-trash me-2"></i>Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <x-tabler.empty-state 
                    title="No Team Members" 
                    message="Add team members to collaborate on this project"
                    icon="ti ti-users"
                />
                @endif
            </div>

            {{-- Cost Tab --}}
            <div class="tab-pane fade" id="project-cost" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Project Costs</h4>
                    <x-tabler.button 
                        href="javascript:void(0)" 
                        class="btn-primary btn-sm ajax-modal-btn" 
                        data-url="{{ route('projects.costs.create-modal', $project) }}"
                        data-modal-title="Record New Cost"
                        icon="ti ti-plus" 
                        text="Add Cost" 
                    />
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-primary-lt">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="text-muted">Total Cost</div>
                                        <div class="h2 mb-0">Rp {{ number_format($statistics['total_cost'], 0, ',', '.') }}</div>
                                    </div>
                                    <i class="ti ti-cash text-primary fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success-lt">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="text-muted">Approved Costs</div>
                                        <div class="h2 mb-0">Rp {{ number_format($project->costs()->where('approval_status', 'approved')->sum('amount'), 0, ',', '.') }}</div>
                                    </div>
                                    <i class="ti ti-check text-success fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($project->costs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Author</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->costs->sortByDesc('created_at') as $cost)
                            <tr>
                                <td>{{ formatTanggalIndo($cost->cost_date) }}</td>
                                <td>{{ $cost->cost_desc }}</td>
                                <td>
                                    <span class="badge bg-{{ $cost->cost_type === 'in_cash' ? 'blue' : 'green' }}-lt">
                                        {{ str_replace('_', ' ', $cost->cost_type) }}
                                    </span>
                                </td>
                                <td class="fw-bold">Rp {{ number_format($cost->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $cost->approval_status_badge_class }}">
                                        {{ ucfirst($cost->approval_status) }}
                                    </span>
                                </td>
                                <td>{{ $cost->author->name ?? 'Unknown' }}</td>
                                <td class="text-center">
                                    <div class="btn-list justify-content-center">
                                        <x-tabler.button 
                                            href="javascript:void(0)" 
                                            class="btn-icon btn-sm ajax-modal-btn" 
                                            data-url="{{ route('projects.costs.edit-modal', [$project, $cost]) }}"
                                            data-modal-title="Edit Cost"
                                            icon="ti ti-edit"
                                            text=""
                                        />
                                        <a href="javascript:void(0)" 
                                           class="btn btn-icon btn-sm btn-outline-danger ajax-delete" 
                                           data-url="{{ route('projects.costs.destroy', [$project, $cost]) }}"
                                           data-title="Delete this cost record?">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <x-tabler.empty-state 
                    title="No Costs Recorded" 
                    message="Add costs to track project expenses"
                    icon="ti ti-cash"
                />
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
@endpush

@push('scripts')
<script type="module">
// jKanban logic ada di resources/js/helpers/projects-kanban.js
document.addEventListener('DOMContentLoaded', function() {
    window.initProjectsKanban({
        element: '#taskKanban',
        loadUrl: '{{ route('projects.tasks.kanban-data', $project) }}',
        moveUrl: '{{ route('projects.tasks.move', [$project, '__TASK_ID__']) }}',
        createUrl: '{{ route('projects.tasks.create-modal', $project) }}',
        widthBoard: '280px',
        boards: ['todo', 'in_progress', 'done']
    });

    window.initProjectTabPersistence({{ $project->project_id }});
});
</script>
@endpush
