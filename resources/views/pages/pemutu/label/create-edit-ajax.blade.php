@php
    $item = $label ?? new \stdClass();
    $method = isset($label) ? 'PUT' : 'POST';
    $route = isset($label) ? route('pemutu.label.update', $label->encrypted_label_id) : route('pemutu.label.store');
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
        <x-tabler.form-select id="parent_id" name="parent_id" label="Parent Label">
            <option value="">-- Menjadi Parent (Top Level) --</option>
            @foreach($parents as $parent)
                @if(isset($label) && $parent->label_id == $label->label_id)
                    @continue
                @endif
                <option value="{{ $parent->encrypted_label_id }}" {{ (old('parent_id', $item->parent_id ?? '') == $parent->label_id) || (request('parent_id') == $parent->encrypted_label_id) ? 'selected' : '' }}>
                    {{ $parent->name }}
                </option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <label class="form-label">Warna Label</label>
        <div class="row g-2">
            @php
                $colors = ['blue', 'azure', 'indigo', 'purple', 'pink', 'red', 'orange', 'yellow', 'lime', 'green', 'teal', 'cyan', 'dark', 'secondary'];
                $selectedColor = old('color', $item->color ?? 'blue');
            @endphp
            @foreach($colors as $color)
            <div class="col-auto">
                <label class="form-colorinput">
                    <input name="color" type="radio" value="{{ $color }}" class="form-colorinput-input" {{ $selectedColor === $color ? 'checked' : '' }}>
                    <span class="form-colorinput-color bg-{{ $color }}"></span>
                </label>
            </div>
            @endforeach
        </div>
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
</x-tabler.form-modal>
