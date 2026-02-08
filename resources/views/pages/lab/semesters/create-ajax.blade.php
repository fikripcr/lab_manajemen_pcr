<div class="modal-header">
    <h5 class="modal-title">Add New Semester</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="createSemesterForm" action="{{ route('semesters.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="tahun_ajaran" class="form-label required">Tahun Ajaran</label>
            <input type="text" class="form-control"
                   id="tahun_ajaran" name="tahun_ajaran"
                   value="{{ old('tahun_ajaran') }}"
                   placeholder="e.g. 2023/2024" required>
        </div>

        <div class="mb-3">
            <label for="semester" class="form-label required">Semester</label>
            <select class="form-select"
                    id="semester" name="semester" required>
                <option value="">Pilih Semester</option>
                <option value="1" {{ old('semester') == 1 ? 'selected' : '' }}>Ganjil</option>
                <option value="2" {{ old('semester') == 2 ? 'selected' : '' }}>Genap</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label required">Start Date</label>
            <input type="date" class="form-control"
                   id="start_date" name="start_date"
                   value="{{ old('start_date') }}" required>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label required">End Date</label>
            <input type="date" class="form-control"
                   id="end_date" name="end_date"
                   value="{{ old('end_date') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                <span class="form-check-label">Set as Active Semester</span>
            </label>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Create Semester" />
    </div>
</form>