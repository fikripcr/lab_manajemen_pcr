<x-tabler.form-modal 
    :title="$entitas->exists ? 'Edit Entitas Terkait' : 'Tambah Entitas Terkait'" 
    :route="$entitas->exists ? route('Kegiatan.rapat.entitas.update', $entitas->encrypted_rapatentitas_id) : route('Kegiatan.rapat.entitas.store')" 
    :method="$entitas->exists ? 'PUT' : 'POST'"
>
    <input type="hidden" name="rapat_id" value="{{ $rapat->encrypted_rapat_id }}">
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="model" 
                label="Model Entitas" 
                type="text" 
                value="{{ old('model', $entitas->model) }}"
                placeholder="Departemen, Proyek, dll" 
                required="true" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="model_id" 
                label="ID Entitas" 
                type="number" 
                value="{{ old('model_id', $entitas->model_id) }}"
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
                value="{{ old('keterangan', $entitas->keterangan) }}"
                placeholder="Masukkan keterangan" 
                rows="3" 
            />
        </div>
    </div>
</x-tabler.form-modal>
