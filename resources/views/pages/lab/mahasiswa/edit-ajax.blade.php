<div class="modal-header">
    <h5 class="modal-title">Edit Mahasiswa</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('lab.mahasiswa.update', $mahasiswa) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
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
                <div class="mb-3">
                    <label class="form-label required" for="orgunit_id">Program Studi</label>
                    <select name="orgunit_id" id="orgunit_id" class="form-select @error('orgunit_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach($prodiList as $prodi)
                            <option value="{{ $prodi->orgunit_id }}" {{ old('orgunit_id', $mahasiswa->orgunit_id) == $prodi->orgunit_id ? 'selected' : '' }}>
                                {{ $prodi->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('orgunit_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Batal" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan Perubahan" />
    </div>
</form>
