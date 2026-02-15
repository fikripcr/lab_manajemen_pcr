<form class="ajax-form" action="{{ route('sys.roles.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalActionLabel">Create New Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-input name="name" label="Role Name" id="name" required="true" placeholder="e.g. Admin, Editor" />
            <div class="form-text">Permissions can be assigned after creating the role.</div>
        </div>
    </div>
    
    <div class="modal-footer">
        <x-tabler.button type="cancel" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" />
    </div>
</form>
