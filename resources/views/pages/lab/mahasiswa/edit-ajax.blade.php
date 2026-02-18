<x-tabler.form-modal
    title="Edit Mahasiswa"
    route="{{ route('lab.mahasiswa.update', $mahasiswa) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <x-tabler.flash-message />
    
    @if($mahasiswa->user)
    <div class="alert alert-info">
        <strong>Info User Terkoneksi:</strong> {{ $mahasiswa->user->name }} ({{ $mahasiswa->user->email }})
        <br><small>Role: {{ $mahasiswa->user->roles->pluck('name')->implode(', ') }}</small>
    </div>
    @else
    <div class="alert alert-warning">
        <strong>Belum terkoneksi dengan user</strong>
    </div>
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="nim" 
                label="NIM" 
                type="text" 
                value="{{ old('nim', $mahasiswa->nim) }}"
                placeholder="Masukkan NIM" 
                required="true" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="nama" 
                label="Nama Mahasiswa" 
                type="text" 
                value="{{ old('nama', $mahasiswa->nama) }}"
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
                value="{{ old('email', $mahasiswa->email) }}"
                placeholder="mahasiswa@example.com" 
                required="true" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-select
                name="orgunit_id"
                label="Program Studi"
                required="true"
            >
                <option value="">-- Pilih Program Studi --</option>
                @foreach($prodiList as $prodi)
                    <option value="{{ $prodi->orgunit_id }}" {{ old('orgunit_id', $mahasiswa->orgunit_id) == $prodi->orgunit_id ? 'selected' : '' }}>
                        {{ $prodi->name }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
    </div>
</x-tabler.form-modal>
