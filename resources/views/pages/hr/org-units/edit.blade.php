<div class="modal-header">
    <h5 class="modal-title">Edit Unit Organisasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.org-units.update', $orgUnit) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-select id="parent_id" name="parent_id" label="Parent Unit" class="select2-offline" data-dropdown-parent="#modalAction">
                <option value="">No Parent (Root)</option>
                @foreach($units as $u)
                    <option value="{{ $u->org_unit_id }}" {{ $orgUnit->parent_id == $u->org_unit_id ? 'selected' : '' }}>
                        {{ $u->name_display ?? $u->name }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="mb-3">
            <x-tabler.form-input name="name" label="Nama Unit" id="name" value="{{ $orgUnit->name }}" required="true" />
        </div>
        <div class="mb-3">
            <x-tabler.form-input name="code" label="Kode Unit" id="code" value="{{ $orgUnit->code }}" />
        </div>
        <div class="mb-3">
            <x-tabler.form-select id="type" name="type" label="Tipe" required="true">
                <option value="" disabled>Pilih Tipe</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}" {{ $orgUnit->type == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </x-tabler.form-select>
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
