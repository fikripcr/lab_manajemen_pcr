<div class="modal-header">
    <h5 class="modal-title">Add Unit {{ $parent ? 'to ' . $parent->name : '' }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemtu.org-units.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
            <div class="col-md-12 mb-3">
                <label for="parent_id" class="form-label">Parent Unit</label>
                <select class="form-select select2-offline" id="parent_id" name="parent_id" data-dropdown-parent="#modalAction">
                    <option value="">No Parent (Root)</option>
                    @foreach($units as $u)
                        <option value="{{ $u->orgunit_id }}" {{ ($parent && $parent->orgunit_id == $u->orgunit_id) ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        <div class="mb-3">
            <label for="name" class="form-label required">Unit Name</label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Departemen Komputer">
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="text" class="form-control" id="code" name="code" placeholder="e.g. JTK">
        </div>
        <div class="mb-3">
            <label for="type" class="form-label required">Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="" disabled selected>Select Type</option>
                @foreach($types as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan" />
    </div>
</form>
