<x-tabler.form-modal
    title="Edit Unit Organisasi"
    route="{{ route('shared.struktur-organisasi.update', $orgUnit->orgunit_id) }}"
    method="PUT"
    submitText="Update"
>
    <div class="mb-3">
        <x-tabler.form-select id="parent_id" name="parent_id" label="Parent Unit" class="select2-offline" data-dropdown-parent="#modalAction">
            <option value="">No Parent (Root)</option>
            @foreach($parents as $u)
                <option value="{{ $u->orgunit_id }}" {{ $orgUnit->parent_id == $u->orgunit_id ? 'selected' : '' }}>
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
        <x-tabler.form-checkbox 
            name="is_active" 
            label="Aktif" 
            value="1" 
            :checked="$orgUnit->is_active" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea name="description" label="Deskripsi" :value="$orgUnit->description" />
    </div>
</x-tabler.form-modal>
