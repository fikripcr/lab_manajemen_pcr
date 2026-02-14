<div class="modal-header">
    <h5 class="modal-title">Edit Label</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.labels.update', $label->label_id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <label for="type_id" class="form-label required">Type</label>
            <select class="form-select" id="type_id" name="type_id" required>
                <option value="">Select Type</option>
                @foreach($types as $type)
                    <option value="{{ $type->labeltype_id }}" {{ $label->type_id == $type->labeltype_id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
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
            <x-tabler.form-input 
                name="description" 
                label="Description" 
                type="textarea" 
                value="{{ old('description', $label->description) }}"
                rows="3" 
            />
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Update" />
    </div>
</form>
