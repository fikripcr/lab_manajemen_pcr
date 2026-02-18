<x-tabler.form-modal
    title="Edit User"
    route="{{ route('lab.users.update', $user->encrypted_id) }}"
    method="PUT"
    submitText="Update User"
    enctype="multipart/form-data"
>
    <x-tabler.flash-message />
    
    <x-tabler.form-input name="name" label="Full Name" value="{{ $user->name }}" placeholder="John Doe" required />

    <x-tabler.form-input type="email" name="email" label="Email" value="{{ $user->email }}" placeholder="john@example.com" required />

    <x-tabler.form-input type="password" name="password" label="New Password (Optional)" placeholder="••••••••" help="Leave blank to keep current password" />
    <x-tabler.form-input type="password" name="password_confirmation" label="Confirm New Password" placeholder="••••••••" />

    <x-tabler.form-select 
        id="role" 
        name="role" 
        label="Role(s)"
        placeholder="Select roles..." 
        multiple
        type="select2"
        :options="$roles->pluck('name', 'name')->toArray()"
        :selected="old('role', $user->roles->pluck('name')->toArray())" 
        required="true"
        class="mb-3"
    />
    @error('role')
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    <x-tabler.form-input type="date" name="expired_at" label="Expiration Date (Optional)" value="{{ $user->expired_at ? $user->expired_at->format('Y-m-d') : '' }}" help="Leave empty for no expiration." />
    
    <x-tabler.form-input type="file" name="avatar" label="Avatar (Optional)" accept="image/png, image/jpeg, image/gif" help="Allowed formats: jpeg, png, jpg, gif. Max size: 2MB." />
</x-tabler.form-modal>
