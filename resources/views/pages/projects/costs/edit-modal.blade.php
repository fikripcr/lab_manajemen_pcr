@php
    $actionUrl = $cost->exists 
        ? route('projects.costs.update', [$project, $cost]) 
        : route('projects.costs.store', $project);
@endphp

<form action="{{ $actionUrl }}" method="POST" class="ajax-form">
    @csrf
    @if($cost->exists)
        @method('PUT')
    @endif

    <div class="modal-body">
        <x-tabler.form-select 
            name="project_task_id" 
            label="Associated Task (Optional)" 
            placeholder="Select Task"
        >
            <option value="">No Task</option>
            @foreach($tasks as $task)
                <option value="{{ $task->project_task_id }}" {{ $cost->project_task_id == $task->project_task_id ? 'selected' : '' }}>
                    {{ $task->task_title }}
                </option>
            @endforeach
        </x-tabler.form-select>

        <x-tabler.form-input 
            name="cost_desc" 
            label="Description" 
            placeholder="e.g. Hosting Subscription" 
            value="{{ $cost->cost_desc }}" 
            required
        />

        <x-tabler.form-select 
            name="cost_type" 
            label="Type" 
            required
        >
            <option value="out_cash" {{ $cost->cost_type == 'out_cash' ? 'selected' : '' }}>Out (Expense)</option>
            <option value="in_cash" {{ $cost->cost_type == 'in_cash' ? 'selected' : '' }}>In (Income)</option>
        </x-tabler.form-select>

        <x-tabler.form-input 
            name="amount" 
            label="Amount (Rp)" 
            type="number" 
            value="{{ $cost->amount }}" 
            required
        />

        <x-tabler.form-input 
            name="cost_date" 
            label="Date" 
            type="date" 
            value="{{ $cost->cost_date ? $cost->cost_date->format('Y-m-d') : date('Y-m-d') }}" 
            required
        />
        
        @if(auth()->user()->hasRole('admin'))
        <x-tabler.form-select 
            name="approval_status" 
            label="Approval Status"
        >
            <option value="pending" {{ $cost->approval_status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ $cost->approval_status == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ $cost->approval_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </x-tabler.form-select>
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
        <x-tabler.button type="submit" class="btn-primary" text="{{ $cost->exists ? 'Update Cost' : 'Save Cost' }}" />
    </div>
</form>
