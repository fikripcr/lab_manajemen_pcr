<x-tabler.form-modal
    :title="$task->exists ? 'Edit Task' : 'Create New Task'"
    :route="$task->exists ? route('projects.tasks.update', [$project, $task]) : route('projects.tasks.store', $project)"
    :method="$task->exists ? 'PUT' : 'POST'"
    :id_form="$task->exists ? 'editTaskForm' : 'createTaskForm'"
    submit-text="{{ $task->exists ? 'Update Task' : 'Create Task' }}"
>
    {{-- Hidden field for project_id --}}
    <input type="hidden" name="project_id" value="{{ $project->project_id }}">
    <input type="hidden" name="status" value="{{ $task->status ?? 'todo' }}">

    <div class="mb-3">
        <x-tabler.form-input
            name="task_title"
            label="Task Title"
            :value="old('task_title', $task->task_title)"
            placeholder="Enter task title"
            required="true"
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-textarea
            name="task_desc"
            label="Description"
            rows="4"
            :value="old('task_desc', $task->task_desc)"
            placeholder="Task description..."
        />
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label">Prioritas</label>
        <div class="form-selectgroup">
            @foreach(['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi', 'urgent' => 'Mendesak'] as $val => $label)
            <label class="form-selectgroup-item">
                <input type="radio" name="priority" value="{{ $val }}" class="form-selectgroup-input" {{ old('priority', $task->priority ?? 'medium') == $val ? 'checked' : '' }}>
                <span class="form-selectgroup-label">{{ $label }}</span>
            </label>
            @endforeach
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-select
                name="assignee_id"
                label="Assignee"
                :options="$users->pluck('name', 'id')->prepend('Unassigned', '')"
                :selected="old('assignee_id', $task->assignee_id)"
                searchable="true"
            />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input
                name="due_date"
                type="date"
                label="Due Date"
                :value="old('due_date', $task->due_date?->format('Y-m-d'))"
            />
        </div>
    </div>

    @if($project->is_agile)
    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-select
                name="project_phase_id"
                label="Phase"
                :options="$phases->pluck('phase_name', 'project_phase_id')->prepend('None', '')"
                :selected="old('project_phase_id', $task->project_phase_id)"
                searchable="true"
            />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-select
                name="project_sprint_id"
                label="Sprint"
                :options="$sprints->pluck('sprint_name', 'project_sprint_id')->prepend('None', '')"
                :selected="old('project_sprint_id', $task->project_sprint_id)"
                searchable="true"
            />
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Bobot</label>
            <div class="form-selectgroup">
                @foreach([1 => 'Ringan', 5 => 'Sedang', 10 => 'Berat'] as $val => $label)
                <label class="form-selectgroup-item">
                    <input type="radio" name="weight" value="{{ $val }}" class="form-selectgroup-input" {{ old('weight', $task->weight ?? 1) == $val ? 'checked' : '' }}>
                    <span class="form-selectgroup-label">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input
                name="hours_worked"
                type="number"
                label="Hours Worked"
                :value="old('hours_worked', $task->hours_worked ?? 0)"
                min="0"
            />
        </div>
    </div>
</x-tabler.form-modal>
