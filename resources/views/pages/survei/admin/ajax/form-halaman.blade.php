<x-tabler.form-modal
    id_form="formEditHalaman"
    title="Edit Halaman"
    route="{{ route('survei.halaman.update', $halaman->encrypted_halaman_id) }}"
    method="PUT"
>
    <x-tabler.form-input 
        name="judul_halaman" 
        label="Judul Halaman" 
        value="{{ $halaman->judul_halaman }}" 
        placeholder="Masukkan judul halaman..." 
        required 
    />
    
    <x-tabler.form-textarea 
        name="deskripsi_halaman" 
        label="Keterangan" 
        placeholder="Instruksi singkat untuk responden di halaman ini..." 
        rows="3"
    >{{ $halaman->deskripsi_halaman }}</x-tabler.form-textarea>
</x-tabler.form-modal>
