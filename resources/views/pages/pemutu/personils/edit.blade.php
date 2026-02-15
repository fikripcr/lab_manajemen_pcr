<div class="modal-header">
    <h5 class="modal-title">Edit Personil</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.personils.update', $personil->personil_id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-input 
                name="nama" 
                label="Nama Lengkap" 
                type="text" 
                value="{{ old('nama', $personil->nama) }}"
                placeholder="Nama Personil" 
                required="true" 
            />
        </div>
        <div class="mb-3">
            <x-tabler.form-input 
                name="email" 
                label="Email" 
                type="email" 
                value="{{ old('email', $personil->email) }}"
                placeholder="example@pcr.ac.id" 
                help="Used to link with User account automatically." 
            />
        </div>
        <div class="mb-3">
            <x-tabler.form-select 
                name="org_unit_id" 
                label="Unit Organisasi" 
                type="select2" 
                :options="$units->pluck('name', 'orgunit_id')->toArray()"
                :selected="old('org_unit_id', $personil->org_unit_id)" 
                placeholder="Select Unit" 
            />
        </div>
        <div class="mb-3">
            <x-tabler.form-select 
                name="jenis" 
                label="Jenis" 
                :options="[ 
                    'Dosen' => 'Dosen',
                    'Staff' => 'Staff',
                    'Mahasiswa' => 'Mahasiswa',
                    'Lainnya' => 'Lainnya'
                ]"
                :selected="old('jenis', $personil->jenis)" 
            />
        </div>
        <div class="mb-3">
            <x-tabler.form-input 
                name="external_id" 
                label="NIP / ID External" 
                type="text" 
                value="{{ old('external_id', $personil->external_id) }}"
                placeholder="NIP / ID External" 
            />
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Update" />
    </div>
</form>
