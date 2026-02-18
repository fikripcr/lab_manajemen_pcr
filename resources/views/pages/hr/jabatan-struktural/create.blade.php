<x-tabler.form-modal
    title="Tambah Jabatan Struktural"
    route="{{ route('hr.jabatan-struktural.store') }}"
    method="POST"
    submitText="Simpan"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="nama" label="Nama Jabatan" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-select name="parent_id" label="Parent (Atasan)">
                <option value="">- Pilih Parent -</option>
                @foreach($parents as $id => $nama)
                    <option value="{{ $id }}">{{ $nama }}</option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Aktif" 
                value="1" 
                checked 
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
