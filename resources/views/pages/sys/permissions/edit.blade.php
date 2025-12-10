<form class="ajax-form" action="{{route('sys.permissions.update',$permission->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <label for="editName" class="form-label">Permission Name</label>
            <input type="text" class="form-control" id="editName" name="name" value="{{ $permission->name }}" required>
            <div class="form-text">Use lowercase letters and underscores only (e.g., manage users, view dashboard)</div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update Permission</button>
    </div>
</form>
