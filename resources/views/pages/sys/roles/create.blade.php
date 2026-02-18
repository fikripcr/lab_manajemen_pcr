<x-tabler.form-modal
    title="Create New Role"
    route="{{ route('sys.roles.store') }}"
>
    <div class="mb-3">
        <x-tabler.form-input name="name" label="Role Name" id="name" required="true" placeholder="e.g. Admin, Editor" />
        <div class="form-text">Permissions can be assigned after creating the role.</div>
    </div>
</x-tabler.form-modal>
