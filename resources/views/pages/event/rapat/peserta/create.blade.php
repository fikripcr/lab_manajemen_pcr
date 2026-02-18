<x-tabler.form-modal 
    title="Tambah Peserta Rapat" 
    route="{{ route('Kegiatan.rapat.peserta.store') }}" 
    method="POST"
>
    <input type="hidden" name="rapat_id" value="{{ $rapat->rapat_id }}">
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select 
                name="user_id" 
                label="Peserta" 
                type="select2" 
                :options="$users->pluck('name', 'id')->toArray()"
                :selected="old('user_id')" 
                placeholder="Pilih peserta" 
                required="true" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="jabatan" 
                label="Jabatan" 
                type="text" 
                value="{{ old('jabatan') }}"
                placeholder="Masukkan jabatan" 
                required="true" 
            />
        </div>
    </div>
</x-tabler.form-modal>
