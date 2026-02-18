<x-tabler.form-modal
    title="Edit Permission"
    route="{{ route('sys.permissions.update', $permission->encryptedId) }}"
    method="PUT"
>
    <div class="mb-3">
        <x-tabler.form-input name="name" label="Permission Name" id="editName" value="{{ $permission->name }}" required="true" />
        <div class="form-text">Use lowercase letters and underscores only (e.g., manage users, view dashboard)</div>
    </div>
</x-tabler.form-modal>
