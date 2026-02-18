<x-tabler.form-modal
    :title="$slideshow->exists ? 'Edit Slideshow' : 'Tambah Slideshow'"
    :route="$slideshow->exists ? route('shared.slideshow.update', $slideshow->hashid) : route('shared.slideshow.store')"
    :method="$slideshow->exists ? 'PUT' : 'POST'"
    enctype="multipart/form-data"
    submitText="Simpan"
>
    <x-tabler.flash-message />
    
    <div class="mb-3">
        <x-tabler.form-input 
            name="slideshow_image" 
            label="Gambar Slideshow" 
            type="file" 
            :required="!$slideshow->exists"
            accept="image/*"
            class="filepond-input"
            help="Ukuran yang disarankan 1200x600 px."
        />
    </div>

    <x-tabler.form-input 
        name="title" 
        label="Judul (Opsional)" 
        value="{{ $slideshow->title }}"
        placeholder="Judul Slideshow"
    />

    <x-tabler.form-textarea 
        name="caption" 
        label="Keterangan (Opsional)"
        placeholder="Deskripsi singkat slideshow"
    >{{ $slideshow->caption }}</x-tabler.form-textarea>

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="link" 
                label="Link Redirect (Opsional)" 
                value="{{ $slideshow->link }}"
                placeholder="https://example.com"
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="seq" 
                label="Urutan" 
                type="number"
                value="{{ $slideshow->seq ?? 0 }}"
            />
        </div>
    </div>

    <div class="mt-3">
        <label class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" {{ $slideshow->exists ? ($slideshow->is_active ? 'checked' : '') : 'checked' }}>
            <span class="form-check-label">Aktifkan Slideshow</span>
        </label>
    </div>
</x-tabler.form-modal>
