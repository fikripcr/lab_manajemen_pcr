<x-tabler.form-modal
    id_form="{{ $period->exists ? 'editPeriodRequestForm' : 'createPeriodRequestForm' }}"
    title="{{ $period->exists ? 'Update Periode Request Software' : 'Tambah Periode Request Software' }}"
    route="{{ $period->exists ? route('lab.periode-request.update', $period->encrypted_periodsoftreq_id) : route('lab.periode-request.store') }}"
    method="{{ $period->exists ? 'PUT' : 'POST' }}"
>
    <div class="mb-3">
        <x-tabler.form-select 
            name="semester_id" 
            label="Semester" 
            :options="$semesters->mapWithKeys(fn($s) => [$s->semester_id => $s->tahun_ajaran . ' - ' . $s->semester])->toArray()" 
            selected="{{ $period->semester_id }}"
            placeholder="-- Pilih Semester --" 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            name="nama_periode" 
            label="Nama Periode" 
            value="{{ old('nama_periode', $period->nama_periode) }}"
            placeholder="Misal: Periode Ganjil 2024/2025" 
            required 
        />
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                type="date" 
                name="start_date" 
                label="Tanggal Mulai" 
                value="{{ old('start_date', $period->start_date ? $period->start_date->format('Y-m-d') : '') }}"
                required 
            />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                type="date" 
                name="end_date" 
                label="Tanggal Selesai" 
                value="{{ old('end_date', $period->end_date ? $period->end_date->format('Y-m-d') : '') }}"
                required 
            />
        </div>
    </div>

    <div class="mb-3">
        <x-tabler.form-checkbox 
            name="is_active" 
            label="Set as Active Period" 
            value="1" 
            :checked="old('is_active', $period->is_active)" 
            switch 
        />
    </div>
</x-tabler.form-modal>
