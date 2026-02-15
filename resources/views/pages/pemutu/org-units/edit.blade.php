<div class="modal-header">
    <h5 class="modal-title">Edit Unit</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.org-units.update', $orgUnit->orgunit_id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
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
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Update" />
    </div>
</form>
