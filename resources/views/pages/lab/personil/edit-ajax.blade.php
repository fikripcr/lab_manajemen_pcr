<div class="modal-header">
    <h5 class="modal-title">Edit Personil</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('lab.personil.update', $personil) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
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
                    name="jabatan" 
                    label="Jabatan" 
                    type="text" 
                    value="{{ old('jabatan', $personil->jabatan) }}"
                    placeholder="Kepala Lab" 
                    required="true" 
                />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Batal" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan Perubahan" />
    </div>
</form>
