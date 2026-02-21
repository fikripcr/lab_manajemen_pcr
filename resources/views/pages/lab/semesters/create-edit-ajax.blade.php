<x-tabler.form-modal
    id_form="{{ $semester->exists ? 'editSemesterForm' : 'createSemesterForm' }}"
    title="{{ $semester->exists ? 'Edit Semester' : 'Add New Semester' }}"
    route="{{ $semester->exists ? route('lab.semesters.update', $semester->encrypted_semester_id) : route('lab.semesters.store') }}"
    method="{{ $semester->exists ? 'PUT' : 'POST' }}"
    submitText="{{ $semester->exists ? 'Update Semester' : 'Create Semester' }}"
>
    <div class="mb-3">
        <x-tabler.form-input name="tahun_ajaran" label="Tahun Ajaran" value="{{ old('tahun_ajaran', $semester->tahun_ajaran) }}" placeholder="Misal: 2023/2024" required="true" />
    </div>

    <div class="mb-3">
        <x-tabler.form-select name="semester" label="Semester" required="true">
            <option value="">Pilih Semester</option>
            <option value="1" {{ old('semester', $semester->semester) == 1 ? 'selected' : '' }}>Ganjil</option>
            <option value="2" {{ old('semester', $semester->semester) == 2 ? 'selected' : '' }}>Genap</option>
        </x-tabler.form-select>
    </div>

    <div class="mb-3">
        <x-tabler.form-input type="date" name="start_date" id="start_date" label="Start Date" :value="old('start_date', $semester->start_date)" required="true" />
    </div>

    <div class="mb-3">
        <x-tabler.form-input type="date" name="end_date" id="end_date" label="End Date" :value="old('end_date', $semester->end_date)" required="true" />
    </div>

    <div class="mb-3">
        <x-tabler.form-checkbox 
            name="is_active" 
            label="Set as Active Semester" 
            value="1" 
            :checked="old('is_active', $semester->is_active)" 
            switch 
        />
    </div>
</x-tabler.form-modal>
