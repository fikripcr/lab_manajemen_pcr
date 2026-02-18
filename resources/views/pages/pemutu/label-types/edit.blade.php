<x-tabler.form-modal
    title="Edit Tipe Label"
    route="{{ route('pemutu.label-types.update', $labelType->labeltype_id) }}"
    method="PUT"
    submitText="Update"
>
    <div class="mb-3">
        <x-tabler.form-input name="name" label="Nama" value="{{ $labelType->name }}" required="true" />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea name="description" label="Deskripsi" rows="2" value="{{ $labelType->description }}" />
    </div>
    <div class="mb-3">
        <label class="form-label required">Warna</label>
        <div class="d-flex flex-wrap gap-2">
            @foreach(['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'blue', 'azure', 'indigo', 'purple', 'pink', 'red', 'orange', 'yellow', 'lime', 'green', 'teal', 'cyan'] as $color)
            <label class="form-colorinput">
                <input type="radio" name="color" value="{{ $color }}" class="form-colorinput-input" {{ ($labelType->color ?? 'blue') == $color ? 'checked' : '' }}>
                <span class="form-colorinput-color bg-{{ $color }}"></span>
            </label>
            @endforeach
        </div>
    </div>
</x-tabler.form-modal>
