<x-tabler.form-modal
    title="Tambah Hak Akses"
    route="{{ route('sys.permissions.store') }}"
>
    <div class="mb-3">
        <x-tabler.form-input name="name" label="Permission Name" id="name" required="true" />
        <div class="form-text">Use lowercase letters and underscores only (e.g., manage users, view dashboard)</div>
    </div>
</x-tabler.form-modal>
