@php
    $item = $labelType ?? new \stdClass();
    $method = isset($labelType) ? 'PUT' : 'POST';
    $route = isset($labelType) ? route('pemutu.label-types.update', $labelType->labeltype_id) : route('pemutu.label-types.store');
    $title = isset($labelType) ? 'Edit Tipe Label' : 'Tambah Tipe Label';
    $submitText = isset($labelType) ? 'Update' : 'Simpan';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$submitText"
>
    <div class="mb-3">
        <x-tabler.form-input 
            name="name" 
            label="Nama" 
            value="{{ old('name', $item->name ?? '') }}"
            required="true" 
            placeholder="cth: Kategori Indikator" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea 
            name="description" 
            label="Deskripsi" 
            rows="2" 
            value="{{ old('description', $item->description ?? '') }}" 
        />
    </div>
    <div class="mb-3">
        <label class="form-label required">Warna</label>
        <div class="d-flex flex-wrap gap-2">
            @foreach(['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'blue', 'azure', 'indigo', 'purple', 'pink', 'red', 'orange', 'yellow', 'lime', 'green', 'teal', 'cyan'] as $color)
            <label class="form-colorinput">
                <input type="radio" name="color" value="{{ $color }}" class="form-colorinput-input" {{ (old('color', $item->color ?? 'blue') == $color) ? 'checked' : '' }}>
                <span class="form-colorinput-color bg-{{ $color }}"></span>
            </label>
            @endforeach
        </div>
    </div>
</x-tabler.form-modal>
