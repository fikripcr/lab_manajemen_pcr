<x-tabler.form-modal
    id="modal-add-agenda"
    title="Tambah Agenda Rapat"
    :route="route('Kegiatan.rapat.agenda.store', $rapat->encrypted_rapat_id)"
    redirect="false"
>
    <input type="hidden" name="rapat_id" value="{{ $rapat->encrypted_rapat_id }}">
    
    <x-tabler.form-input 
        name="judul_agenda" 
        label="Judul Agenda" 
        placeholder="Contoh: Pembahasan KPI 2024" 
        required="true" 
    />

    <x-tabler.form-textarea 
        name="isi" 
        label="Catatan Pembahasan (Opsional)" 
        rows="4" 
        placeholder="Catatan awal untuk agenda ini..."
    />
</x-tabler.form-modal>
