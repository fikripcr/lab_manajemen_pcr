<x-tabler.form-modal
    title="Edit Personil"
    route="{{ route('lab.personil.update', $personil) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <x-tabler.flash-message />
    
    @if($personil->user)
    <div class="alert alert-info">
        <strong>Info User Terkoneksi:</strong> {{ $personil->user->name }} ({{ $personil->user->email }})
        <br><small>Role: {{ $personil->user->roles->pluck('name')->implode(', ') }}</small>
    </div>
    @else
    <div class="alert alert-warning">
        <strong>Belum terkoneksi dengan user</strong>
    </div>
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="nip" 
                label="NIP/NIK" 
                type="text" 
                value="{{ old('nip', $personil->nip) }}"
                placeholder="Masukkan NIP/NIK" 
                required="true" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="nama" 
                label="Nama Personil" 
                type="text" 
                value="{{ old('nama', $personil->nama) }}"
                placeholder="Masukkan Nama Lengkap" 
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
                value="{{ old('email', $personil->email) }}"
                placeholder="personil@example.com" 
                required="true" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="posisi" 
                label="Posisi" 
                type="text" 
                value="{{ old('posisi', $personil->posisi) }}"
                placeholder="Kepala Lab" 
                required="true" 
            />
        </div>
    </div>
</x-tabler.form-modal>
