@php
    $item = $label ?? new \stdClass();
    $method = isset($label) ? 'PUT' : 'POST';
    $route = isset($label) ? route('pemutu.labels.update', $label->label_id) : route('pemutu.labels.store');
    $title = isset($label) ? 'Ubah Label' : 'Tambah Label';
    $submitText = isset($label) ? 'Update' : 'Simpan';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$submitText"
>
    <div class="mb-3">
        <x-tabler.form-select id="type_id" name="type_id" label="Tipe" required="true">
            <option value="">Pilih Tipe</option>
            @foreach($types as $type)
                <option value="{{ $type->labeltype_id }}" {{ (old('type_id', $item->type_id ?? '') == $type->labeltype_id) || (request('type_id') == $type->labeltype_id) ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="name" 
            label="Nama" 
            type="text" 
            value="{{ old('name', $item->name ?? '') }}"
            placeholder="Nama Label" 
            required="true" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="slug" 
            label="Slug" 
            type="text" 
            value="{{ old('slug', $item->slug ?? '') }}"
            placeholder="Otomatis jika kosong" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea 
            name="description" 
            label="Deskripsi" 
            value="{{ old('description', $item->description ?? '') }}"
            rows="3" 
        />
    </div>
</x-tabler.form-modal>
