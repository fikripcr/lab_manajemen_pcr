<div class="modal-header">
    <h5 class="modal-title">Create Label Type</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemtu.label-types.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="name" class="form-label required">Name</label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Kategori Indikator">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label required">Color</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach(['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'blue', 'azure', 'indigo', 'purple', 'pink', 'red', 'orange', 'yellow', 'lime', 'green', 'teal', 'cyan'] as $color)
                <label class="form-colorinput">
                    <input type="radio" name="color" value="{{ $color }}" class="form-colorinput-input" {{ $loop->first ? 'checked' : '' }}>
                    <span class="form-colorinput-color bg-{{ $color }}"></span>
                </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan" />
    </div>
</form>
