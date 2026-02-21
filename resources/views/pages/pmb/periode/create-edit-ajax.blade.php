<x-tabler.form-modal
    id_form="{{ $periode->exists ? 'editPeriodeForm' : 'createPeriodeForm' }}"
    title="{{ $periode->exists ? 'Update Periode Pendaftaran' : 'Tambah Periode Pendaftaran' }}"
    route="{{ $periode->exists ? route('pmb.periode.update', $periode->encrypted_periode_id) : route('pmb.periode.store') }}"
    method="{{ $periode->exists ? 'PUT' : 'POST' }}"
>
    <div class="mb-3">
        <x-tabler.form-input 
            name="nama_periode" 
            label="Nama Periode" 
            value="{{ old('nama_periode', $periode->nama_periode) }}"
            placeholder="Contoh: PMB 2024/2025 Gelombang 1" 
            required 
        />
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                type="date" 
                name="tanggal_mulai" 
                label="Tanggal Mulai" 
                value="{{ old('tanggal_mulai', $periode->tanggal_mulai ? \Carbon\Carbon::parse($periode->tanggal_mulai)->format('Y-m-d') : '') }}"
                required 
            />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                type="date" 
                name="tanggal_selesai" 
                label="Tanggal Selesai" 
                value="{{ old('tanggal_selesai', $periode->tanggal_selesai ? \Carbon\Carbon::parse($periode->tanggal_selesai)->format('Y-m-d') : '') }}"
                required 
            />
        </div>
    </div>

    <div class="mb-3">
        <x-tabler.form-checkbox 
            name="is_aktif" 
            label="Status Aktif" 
            value="1" 
            :checked="$periode->is_aktif" 
            switch 
        />
    </div>
</x-tabler.form-modal>
