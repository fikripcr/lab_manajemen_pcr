<x-tabler.form-modal
    title="Tambah Unit Organisasi"
    route="{{ route('shared.struktur-organisasi.store') }}"
    method="POST"
>
    <div class="mb-3">
        <x-tabler.form-select id="parent_id" name="parent_id" label="Parent Unit" class="select2-offline" data-dropdown-parent="#modalAction">
            <option value="">No Parent (Root)</option>
            @foreach($parents as $u)
                <option value="{{ $u->orgunit_id }}">
                    {{ $u->name_display ?? $u->name }}
                </option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input name="name" label="Nama Unit" id="name" required="true" placeholder="e.g. Departemen Komputer" />
    </div>
    <div class="mb-3">
        <x-tabler.form-input name="code" label="Kode Unit" id="code" placeholder="e.g. DKOM" />
    </div>
    <div class="mb-3">
        <x-tabler.form-select id="type" name="type" label="Tipe" required="true">
            <option value="" disabled selected>Pilih Tipe</option>
            @foreach($types as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-checkbox 
            name="is_active" 
            label="Aktif" 
            value="1" 
            checked 
        />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea name="description" label="Deskripsi" placeholder="Keterangan tambahan..." />
    </div>
</x-tabler.form-modal>
