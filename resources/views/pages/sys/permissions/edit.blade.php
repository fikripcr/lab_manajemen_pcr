<form class="ajax-form" action="{{route('sys.permissions.update',$permission->encryptedId)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Edit Permission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label for="editName" class="form-label">Permission Name</label>
            <input type="text" class="form-control" id="editName" name="name" value="{{ $permission->name }}" required>
            <div class="form-text">Use lowercase letters and underscores only (e.g., manage users, view dashboard)</div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="cancel" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" />
    </div>
</form>
