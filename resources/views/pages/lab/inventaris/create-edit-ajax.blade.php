<x-tabler.form-modal
    id_form="{{ $inventaris->exists ? 'editInventarisForm' : 'createInventarisForm' }}"
    title="{{ $inventaris->exists ? 'Edit Inventory' : 'Create New Inventory' }}"
    route="{{ $inventaris->exists ? route('lab.inventaris.update', $inventaris) : route('lab.inventaris.store') }}"
    method="{{ $inventaris->exists ? 'PUT' : 'POST' }}"
    submitText="{{ $inventaris->exists ? 'Update' : 'Create' }}"
>
    <x-tabler.flash-message />

    <x-tabler.form-select 
        name="lab_id" 
        label="Lab" 
        :options="$labs->pluck('name', 'lab_id')->toArray()" 
        selected="{{ old('lab_id', $inventaris->lab_id) }}" 
        placeholder="Select Lab" 
        required 
        class="mb-3" 
    />

    <x-tabler.form-input 
        name="nama_alat" 
        label="Equipment Name" 
        value="{{ old('nama_alat', $inventaris->nama_alat) }}" 
        placeholder="e.g., Laptop, Microscope, etc." 
        required 
    />

    <x-tabler.form-input 
        name="jenis_alat" 
        label="Tipe" 
        value="{{ old('jenis_alat', $inventaris->jenis_alat) }}" 
        placeholder="e.g., Electronic, Chemical, Equipment" 
        required 
    />

    <x-tabler.form-select 
        name="kondisi_terakhir" 
        label="Condition" 
        :options="['Baik' => 'Good', 'Rusak Ringan' => 'Minor Damage', 'Rusak Berat' => 'Major Damage', 'Tidak Dapat Digunakan' => 'Cannot Be Used']" 
        selected="{{ old('kondisi_terakhir', $inventaris->kondisi_terakhir) }}" 
        placeholder="Select Condition" 
        required 
        class="mb-3" 
    />

    <x-tabler.form-input 
        type="date" 
        name="tanggal_pengecekan" 
        label="Last Check Date" 
        value="{{ old('tanggal_pengecekan', $inventaris->tanggal_pengecekan ? $inventaris->tanggal_pengecekan->format('Y-m-d') : date('Y-m-d')) }}" 
        required 
    />
</x-tabler.form-modal>
