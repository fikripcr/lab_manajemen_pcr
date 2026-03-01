<x-tabler.form-modal 
    :title="$permission->exists ? 'Edit Izin' : 'Tambah Izin Baru'" 
    :route="$permission->exists ? route('sys.permissions.update', $permission->encryptedId) : route('sys.permissions.store')" 
    :method="$permission->exists ? 'PUT' : 'POST'" 
>
    @if(!$permission->exists)
        <x-tabler.form-textarea 
            name="name" 
            label="Nama Izin (Slug)" 
            required="true" 
            placeholder="Contoh: sys.roles.index&#10;sys.roles.create&#10;sys.roles.edit" 
            help="Bisa memasukkan banyak nama izin sekaligus, pisahkan dengan baris baru (Enter) atau koma. Gunakan format dot notation (module.feature.action)."
            rows="3"
        >{{ old('name') }}</x-tabler.form-textarea>
    @else
        <x-tabler.form-input 
            name="name" 
            label="Nama Izin (Slug)" 
            required="true" 
            :value="old('name', $permission->name)" 
            placeholder="Contoh: sys.permissions.index" 
            help="Gunakan format dot notation (module.feature.action)"
        />
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="category" 
                label="Kategori" 
                :value="old('category', $permission->category)" 
                placeholder="Contoh: Sistem" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="sub_category" 
                label="Sub Kategori" 
                :value="old('sub_category', $permission->sub_category)" 
                placeholder="Contoh: Izin" 
            />
        </div>
    </div>

    <x-tabler.form-textarea 
        name="description" 
        label="Deskripsi" 
        rows="2"
    >{{ old('description', $permission->description) }}</x-tabler.form-textarea>

    <input type="hidden" name="guard_name" value="web">
</x-tabler.form-modal>
