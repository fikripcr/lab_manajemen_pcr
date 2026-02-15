<div class="modal-header">
    <h5 class="modal-title">Tambah Unit Organisasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('hr.org-units.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-select id="parent_id" name="parent_id" label="Parent Unit" class="select2-offline" data-dropdown-parent="#modalAction">
                <option value="">No Parent (Root)</option>
                @foreach($units as $u)
                    <option value="{{ $u->org_unit_id }}" {{ ($parent && $parent->org_unit_id == $u->org_unit_id) ? 'selected' : '' }}>
                        {{ $u->name_display ?? $u->name }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="mb-3">
            <x-tabler.form-input name="name" label="Nama Unit" id="name" required="true" placeholder="e.g. Departemen Komputer" />
        </div>
        <div class="mb-3">
            <x-tabler.form-input name="code" label="Kode Unit" id="code" placeholder="e.g. DKOM" />
        </div>
        <div class="mb-3">
            <x-tabler.form-select id="type" name="type" label="Tipe" required="true">
                <option value="" disabled selected>Pilih Tipe</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Aktif" 
                value="1" 
                checked 
            />
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Batal" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan" />
    </div>
</form>
