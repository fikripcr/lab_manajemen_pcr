@php
    $actionUrl = isset($member) && $member->exists 
        ? route('projects.members.update', [$project, $member]) 
        : route('projects.members.store', $project);
@endphp

<x-tabler.form-modal 
    id="modalAction" 
    title="{{ isset($member) && $member->exists ? 'Edit Member' : 'Tambah Member' }}" 
    route="{{ $actionUrl }}" 
    method="{{ isset($member) && $member->exists ? 'PUT' : 'POST' }}"
>
    <x-tabler.form-select 
        name="user_id" 
        label="User" 
        placeholder="Select User" 
        required
    >
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ (isset($member) ? $member->user_id : '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </x-tabler.form-select>

    <x-tabler.form-select 
        name="role" 
        label="Role" 
        required
    >
        <option value="member" {{ (isset($member) ? $member->role : '') == 'member' ? 'selected' : '' }}>Member</option>
        <option value="leader" {{ (isset($member) ? $member->role : '') == 'leader' ? 'selected' : '' }}>Leader</option>
        <option value="viewer" {{ (isset($member) ? $member->role : '') == 'viewer' ? 'selected' : '' }}>Viewer</option>
    </x-tabler.form-select>

    <x-tabler.form-input 
        name="alias_position" 
        label="Alias/Position" 
        placeholder="e.g. Frontend Developer" 
        value="{{ isset($member) ? $member->alias_position : '' }}"
    />

    <x-tabler.form-input 
        name="rate_per_hour" 
        label="Rate Per Hour" 
        type="number" 
        step="0.01" 
        placeholder="e.g. 50000" 
        value="{{ isset($member) ? $member->rate_per_hour : '' }}"
    />
</x-tabler.form-modal>
