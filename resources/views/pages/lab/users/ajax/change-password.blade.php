<x-tabler.form-modal
    id_form="formChangePassword"
    title="Change Password"
    route="{{ route('password.update') }}"
    method="PUT"
    submitText="Change Password"
>
    <x-tabler.form-input 
        type="password" 
        name="current_password" 
        label="Current Password" 
        required 
        autocomplete="current-password" 
    />

    <x-tabler.form-input 
        type="password" 
        name="password" 
        label="New Password" 
        required 
        autocomplete="new-password" 
    />

    <x-tabler.form-input 
        type="password" 
        name="password_confirmation" 
        label="Confirm New Password" 
        required 
        autocomplete="new-password" 
    />
</x-tabler.form-modal>
