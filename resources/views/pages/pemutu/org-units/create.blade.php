<x-tabler.form-modal
    title="Add Unit {{ $parent ? 'to ' . $parent->name : '' }}"
    route="{{ route('pemutu.org-units.store') }}"
    method="POST"
>
    <div class="mb-3">
        <x-tabler.form-select 
            name="parent_id" 
            label="Parent Unit" 
            type="select2" 
            :options="$units->pluck('name', 'orgunit_id')->toArray()"
            :selected="old('parent_id')" 
            placeholder="No Parent (Root)" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="name" 
            label="Unit Name" 
            type="text" 
            value="{{ old('name') }}"
            placeholder="e.g. Departemen Komputer" 
            required="true" 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-input 
            name="code" 
            label="Code" 
            type="text" 
            value="{{ old('code') }}"
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
            :selected="old('type')" 
            required="true" 
        />
    </div>
</x-tabler.form-modal>
