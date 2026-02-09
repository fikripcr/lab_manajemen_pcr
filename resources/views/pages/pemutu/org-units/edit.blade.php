<div class="modal-header">
    <h5 class="modal-title">Edit Unit</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.org-units.update', $orgUnit->orgunit_id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
            <div class="col-md-12 mb-3">
                <label for="parent_id" class="form-label">Parent Unit</label>
                <select class="form-select select2-offline" id="parent_id" name="parent_id" data-dropdown-parent="#modalAction">
                    <option value="">No Parent (Root)</option>
                    @foreach($units as $u)
                        <option value="{{ $u->orgunit_id }}" {{ $orgUnit->parent_id == $u->orgunit_id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        <div class="mb-3">
            <label for="name" class="form-label required">Unit Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $orgUnit->name }}" required>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ $orgUnit->code }}">
        </div>
        <div class="mb-3">
            <label for="type" class="form-label required">Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="" disabled>Select Type</option>
                @foreach($types as $t)
                    <option value="{{ $t }}" {{ $orgUnit->type == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Update" />
    </div>
</form>
