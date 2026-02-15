<div class="modal-header">
    <h5 class="modal-title">Add New Semester</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="createSemesterForm" action="{{ route('semesters.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-input name="tahun_ajaran" label="Tahun Ajaran" placeholder="Misal: 2023/2024" required="true" value="{{ old('tahun_ajaran') }}" />
        </div>

        <div class="mb-3">
            <x-tabler.form-select name="semester" label="Semester" required="true">
                <option value="">Pilih Semester</option>
                <option value="1" {{ old('semester') == 1 ? 'selected' : '' }}>Ganjil</option>
                <option value="2" {{ old('semester') == 2 ? 'selected' : '' }}>Genap</option>
            </x-tabler.form-select>
        </div>

        <div class="mb-3">
            <x-tabler.form-input type="date" name="start_date" id="start_date" label="Start Date" :value="old('start_date')" required="true" />
        </div>

        <div class="mb-3">
            <x-tabler.form-input type="date" name="end_date" id="end_date" label="End Date" :value="old('end_date')" required="true" />
        </div>

        <div class="mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Set as Active Semester" 
                value="1" 
                :checked="old('is_active')" 
                switch 
            />
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Create Semester" />
    </div>
</form>