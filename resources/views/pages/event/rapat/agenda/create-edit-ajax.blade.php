<x-tabler.form-modal
    title="{{ $agenda->exists ? 'Edit Agenda' : 'Tambah Agenda' }}"
    :route="$agenda->exists ? route('Kegiatan.rapat.agenda.update', $agenda->encrypted_rapatagenda_id) : route('Kegiatan.rapat.agenda.store', $rapat->encrypted_rapat_id)"
    :method="$agenda->exists ? 'PUT' : 'POST'"
    redirect="false"
>
    <input type="hidden" name="rapat_id" value="{{ $rapat->encrypted_rapat_id }}">
    
    <x-tabler.form-input 
        name="judul_agenda" 
        label="Judul Agenda" 
        placeholder="Contoh: Pembahasan KPI 2024" 
        value="{{ old('judul_agenda', $agenda->judul_agenda) }}"
        required="true" 
    />

    <x-tabler.form-textarea 
        name="isi" 
        label="Catatan Pembahasan (Opsional)" 
        rows="4" 
        placeholder="Catatan awal untuk agenda ini..."
        value="{{ old('isi', $agenda->isi) }}"
    />
</x-tabler.form-modal>
