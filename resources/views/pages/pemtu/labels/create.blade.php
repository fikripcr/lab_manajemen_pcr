<div class="modal-header">
    <h5 class="modal-title">Create Label</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemtu.labels.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="type_id" class="form-label required">Type</label>
            <select class="form-select" id="type_id" name="type_id" required>
                <option value="">Select Type</option>
                @foreach($types as $type)
                    <option value="{{ $type->labeltype_id }}" {{ request('type_id') == $type->labeltype_id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label required">Name</label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="Label Name">
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug (Optional)</label>
            <input type="text" class="form-control" id="slug" name="slug" placeholder="Auto-generated if empty">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Create" />
    </div>
</form>
