<x-tabler.form-modal
    title="Edit Personil"
    route="{{ route('shared.personil.update', $personil->personil_id) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <x-tabler.flash-message />
    
    @if($personil->user)
    <div class="alert alert-info py-2">
        <div class="d-flex align-items-center">
            <i class="ti ti-info-circle me-2"></i>
            <div>
                <strong>User Terkoneksi:</strong> {{ $personil->user->name }}
                <br><small>Role: {{ $personil->user->roles->pluck('name')->implode(', ') }}</small>
            </div>
        </div>
    </div>
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="nip" 
                label="NIP/NIK" 
                value="{{ $personil->nip }}"
                placeholder="NIP/NIK" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="nama" 
                label="Nama Lengkap" 
                value="{{ $personil->nama }}"
                required="true" 
            />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="email" 
                label="Email" 
                type="email"
                value="{{ $personil->email }}"
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="posisi" 
                label="Posisi" 
                value="{{ $personil->posisi }}"
            />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select 
                name="org_unit_id"
                label="Unit Kerja"
                :options="$units->pluck('name', 'orgunit_id')->toArray()"
                :value="$personil->org_unit_id"
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="vendor" 
                label="Vendor" 
                value="{{ $personil->vendor }}"
            />
        </div>
    </div>

    <div class="mt-3">
        <label class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="status_aktif" {{ $personil->status_aktif ? 'checked' : '' }}>
            <span class="form-check-label">Personil Aktif</span>
        </label>
    </div>
</x-tabler.form-modal>
