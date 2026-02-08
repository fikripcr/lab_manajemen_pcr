<div class="modal-header">
    <h5 class="modal-title">Edit Unit Organisasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.org-units.update', $orgUnit) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Unit</label>
            <select class="form-select select2-offline" id="parent_id" name="parent_id" data-dropdown-parent="#modalAction">
                <option value="">No Parent (Root)</option>
                @foreach($units as $u)
                    <option value="{{ $u->org_unit_id }}" {{ $orgUnit->parent_id == $u->org_unit_id ? 'selected' : '' }}>
                        {{ $u->name_display ?? $u->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label required">Nama Unit</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $orgUnit->name }}" required>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Kode</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ $orgUnit->code }}">
        </div>
        <div class="mb-3">
            <label for="type" class="form-label required">Tipe</label>
            <select class="form-select" id="type" name="type" required>
                <option value="" disabled>Pilih Tipe</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}" {{ $orgUnit->type == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-check">
                <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ $orgUnit->is_active ? 'checked' : '' }}>
                <span class="form-check-label">Aktif</span>
            </label>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Batal" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Update" />
    </div>
</form>
