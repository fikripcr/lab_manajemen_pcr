<x-tabler.form-modal
    title="Create Label"
    route="{{ route('pemutu.labels.store') }}"
    method="POST"
>
    <div class="mb-3">
        <x-tabler.form-select id="type_id" name="type_id" label="Type" required="true">
            <option value="">Select Type</option>
            @foreach($types as $type)
                <option value="{{ $type->labeltype_id }}" {{ request('type_id') == $type->labeltype_id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="name" 
            label="Name" 
            type="text" 
            value="{{ old('name') }}"
            placeholder="Label Name" 
            required="true" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="slug" 
            label="Slug" 
            type="text" 
            value="{{ old('slug') }}"
            placeholder="Auto-generated if empty" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea 
            name="description" 
            label="Description" 
            value="{{ old('description') }}"
            rows="3" 
        />
    </div>
</x-tabler.form-modal>
