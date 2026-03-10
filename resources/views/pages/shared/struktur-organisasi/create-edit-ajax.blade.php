@php
    $item = $orgUnit ?? new \stdClass();
    $method = isset($orgUnit) ? 'PUT' : 'POST';
    $route = isset($orgUnit) ? route('shared.struktur-organisasi.update', $orgUnit) : route('shared.struktur-organisasi.store');
    $title = isset($orgUnit) ? 'Edit Unit Organisasi' : 'Tambah Unit Organisasi';
    $submitText = isset($orgUnit) ? 'Update' : 'Simpan';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$submitText"
>
    <div class="mb-3">
        <x-tabler.form-select 
            id="parent_id" 
            name="parent_id" 
            label="Parent Unit" 
            type="select2" 
            data-dropdown-parent="#modalAction"
            placeholder="No Parent (Root)"
        >
            @foreach($parents as $u)
                @php
                    $uId = $u->encrypted_org_unit_id;
                    $isSelected = (old('parent_id', $item->parent_id ?? '') == $u->orgunit_id || old('parent_id') == $uId);
                @endphp
                <option value="{{ $uId }}" {{ $isSelected ? 'selected' : '' }}>
                    {{ $u->name_display ?? $u->name }}
                </option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="name" 
            label="Nama Unit" 
            id="name" 
            value="{{ old('name', $item->name ?? '') }}"
            required="true" 
            placeholder="e.g. Departemen Komputer" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="code" 
            label="Kode Unit" 
            id="code" 
            value="{{ old('code', $item->code ?? '') }}"
            placeholder="e.g. DKOM" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-select 
            id="type" 
            name="type" 
            label="Tipe" 
            required="true"
            placeholder="Pilih Tipe"
        >
            @foreach($types as $key => $label)
                <option value="{{ $key }}" {{ old('type', $item->type ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-checkbox 
            name="is_active" 
            label="Aktif" 
            value="1" 
            :checked="old('is_active', $item->is_active ?? true)" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea 
            name="description" 
            label="Deskripsi" 
            :value="old('description', $item->description ?? '')"
            placeholder="Keterangan tambahan..." 
        />
    </div>
</x-tabler.form-modal>
