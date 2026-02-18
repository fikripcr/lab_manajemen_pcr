<x-tabler.form-modal
    title="Edit Label"
    route="{{ route('pemutu.labels.update', $label->label_id) }}"
    method="PUT"
    submitText="Update"
>
    <div class="mb-3">
        <x-tabler.form-select id="type_id" name="type_id" label="Type" required="true">
            <option value="">Select Type</option>
            @foreach($types as $type)
                <option value="{{ $type->labeltype_id }}" {{ $label->type_id == $type->labeltype_id ? 'selected' : '' }}>{{ $type->name }}</option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="name" 
            label="Name" 
            type="text" 
            value="{{ old('name', $label->name) }}"
            placeholder="Label Name" 
            required="true" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="slug" 
            label="Slug" 
            type="text" 
            value="{{ old('slug', $label->slug) }}"
            placeholder="Auto-generated if empty" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea 
            name="description" 
            label="Description" 
            value="{{ old('description', $label->description) }}"
            rows="3" 
        />
    </div>
</x-tabler.form-modal>
