<div class="modal-header">
    <h5 class="modal-title">Edit Semester</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="editSemesterForm" action="{{ route('semesters.update', $semester->encrypted_semester_id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-input name="tahun_ajaran" label="Tahun Ajaran" value="{{ old('tahun_ajaran', $semester->tahun_ajaran) }}" placeholder="e.g. 2023/2024" required="true" />
        </div>

        <div class="mb-3">
            <x-tabler.form-select name="semester" label="Semester" required="true">
                <option value="">Pilih Semester</option>
                <option value="1" {{ old('semester', $semester->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="2" {{ old('semester', $semester->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
            </x-tabler.form-select>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label required">Start Date</label>
            <input type="date" class="form-control"
                   id="start_date" name="start_date"
                   value="{{ old('start_date', $semester->start_date) }}" required>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label required">End Date</label>
            <input type="date" class="form-control"
                   id="end_date" name="end_date"
                   value="{{ old('end_date', $semester->end_date) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $semester->is_active) ? 'checked' : '' }}>
                <span class="form-check-label">Set as Active Semester</span>
            </label>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Update Semester" />
    </div>
</form>
