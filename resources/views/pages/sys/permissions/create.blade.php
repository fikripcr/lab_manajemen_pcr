
<form class="ajax-form" action="{{ route('sys.permissions.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Create New Permission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label for="name" class="form-label">Permission Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
            <div class="form-text">Use lowercase letters and underscores only (e.g., manage users, view dashboard)</div>
        </div>
    </div>
    <div class="modal-footer">
        <x-sys.button type="cancel" data-bs-dismiss="modal" />
        <x-sys.button type="submit" />
    </div>
</form>
