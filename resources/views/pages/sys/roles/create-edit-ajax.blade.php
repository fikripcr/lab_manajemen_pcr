<x-tabler.form-modal 
    :title="$role->exists ? 'Edit Peran' : 'Tambah Peran Baru'" 
    :route="$role->exists ? route('sys.roles.update', $role->encryptedId) : route('sys.roles.store')" 
    :method="$role->exists ? 'PUT' : 'POST'" 
>
    @if(!$role->exists)
        <x-tabler.form-textarea 
            name="name" 
            label="Nama Peran" 
            required="true" 
            placeholder="Contoh: Admin&#10;Operator&#10;Pimpinan" 
            help="Bisa memasukkan banyak nama peran sekaligus, pisahkan dengan baris baru (Enter) atau koma."
            rows="3"
        >{{ old('name') }}</x-tabler.form-textarea>
    @else
        <x-tabler.form-input 
            name="name" 
            label="Nama Peran" 
            required="true" 
            :value="old('name', $role->name)" 
            placeholder="Contoh: Admin" 
        />
    @endif
</x-tabler.form-modal>
