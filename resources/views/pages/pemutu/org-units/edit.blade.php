<x-tabler.form-modal
    title="Edit Unit"
    route="{{ route('pemutu.org-units.update', $orgUnit->orgunit_id) }}"
    method="PUT"
    submitText="Update"
>
    <div class="mb-3">
        <x-tabler.form-select 
            name="parent_id" 
            label="Parent Unit" 
            type="select2" 
            :options="$units->pluck('name', 'orgunit_id')->toArray()"
            :selected="old('parent_id', $orgUnit->parent_id)" 
            placeholder="No Parent (Root)" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="name" 
            label="Unit Name" 
            type="text" 
            value="{{ old('name', $orgUnit->name) }}"
            placeholder="e.g. Departemen Komputer" 
            required="true" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="code" 
            label="Code" 
            type="text" 
            value="{{ old('code', $orgUnit->code) }}"
            placeholder="e.g. JTK" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-select 
            name="type" 
            label="Type" 
            :options="[ 
                'Fakultas' => 'Fakultas',
                'Jurusan' => 'Jurusan',
                'Program Studi' => 'Program Studi',
                'Lainnya' => 'Lainnya'
            ]"
            :selected="old('type', $orgUnit->type)" 
            required="true" 
        />
    </div>
</x-tabler.form-modal>
