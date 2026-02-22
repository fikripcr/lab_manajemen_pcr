<x-tabler.form-modal
    id_form="{{ $assignment->exists ? 'editPcAssignmentForm' : 'createPcAssignmentForm' }}"
    title="{{ $assignment->exists ? 'Update Assignment PC' : 'Tambah Assignment PC' }}"
    route="{{ $assignment->exists ? route('lab.jadwal.assignments.update', [$jadwal->encrypted_jadwal_kuliah_id, $assignment->encrypted_pc_assignment_id]) : route('lab.jadwal.assignments.store', $jadwal->encrypted_jadwal_kuliah_id) }}"
    method="{{ $assignment->exists ? 'PUT' : 'POST' }}"
>
    <div class="mb-3">
        <x-tabler.form-select 
            name="user_id" 
            label="Mahasiswa" 
            :options="$mahasiswas->mapWithKeys(fn($m) => [$m->id => $m->name . ' (' . $m->username . ')'])->toArray()" 
            selected="{{ $assignment->user_id }}"
            placeholder="-- Pilih Mahasiswa --" 
            type="select2"
            required 
        />
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-select 
                name="nomor_pc" 
                label="Nomor PC" 
                :options="collect(range(1, $totalPc))->mapWithKeys(function($num) use ($assignedPcs, $assignment) {
                    $disabled = in_array($num, $assignedPcs) && $assignment->nomor_pc != $num;
                    $label = $num . ($disabled ? ' (Already assigned)' : '');
                    return [$num => $label];
                })->toArray()"
                selected="{{ $assignment->nomor_pc }}"
                required 
            />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                type="number" 
                name="nomor_loker" 
                label="Nomor Loker (Opsional)" 
                value="{{ old('nomor_loker', $assignment->nomor_loker) }}"
                placeholder="Contoh: 15" 
            />
        </div>
    </div>
</x-tabler.form-modal>
