<x-tabler.form-modal 
    title="Tambah Entitas Terkait" 
    route="{{ route('Kegiatan.rapat.entitas.store', $rapat) }}" 
    method="POST"
>
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="model" 
                label="Model Entitas" 
                type="text" 
                value="{{ old('model') }}"
                placeholder="Departemen, Proyek, dll" 
                required="true" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="model_id" 
                label="ID Entitas" 
                type="number" 
                value="{{ old('model_id') }}"
                placeholder="Masukkan ID entitas" 
                required="true" 
            />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <x-tabler.form-textarea 
                name="keterangan" 
                label="Keterangan" 
                value="{{ old('keterangan') }}"
                placeholder="Masukkan keterangan" 
                rows="3" 
            />
        </div>
    </div>
</x-tabler.form-modal>
