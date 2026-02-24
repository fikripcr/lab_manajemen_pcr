<form action="{{ route('projects.members.store', $project) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <x-tabler.form-select 
            name="user_id" 
            label="User" 
            placeholder="Select User" 
            required
        >
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $member->user_id == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </x-tabler.form-select>

        <x-tabler.form-select 
            name="role" 
            label="Role" 
            required
        >
            <option value="member" {{ $member->role == 'member' ? 'selected' : '' }}>Member</option>
            <option value="leader" {{ $member->role == 'leader' ? 'selected' : '' }}>Leader</option>
            <option value="viewer" {{ $member->role == 'viewer' ? 'selected' : '' }}>Viewer</option>
        </x-tabler.form-select>

        <x-tabler.form-input 
            name="alias_position" 
            label="Alias/Position" 
            placeholder="e.g. Frontend Developer" 
            value="{{ $member->alias_position }}"
        />

        <x-tabler.form-input 
            name="rate_per_hour" 
            label="Rate Per Hour" 
            type="number" 
            step="0.01" 
            placeholder="e.g. 50000" 
            value="{{ $member->rate_per_hour }}"
        />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Member</button>
    </div>
</form>
