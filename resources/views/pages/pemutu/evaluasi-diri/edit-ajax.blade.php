<x-tabler.form-modal
    :title="'Isi Evaluasi Diri'"
    :route="route('pemutu.evaluasi-diri.update', $indikator->encrypted_indikator_id)"
    method="POST" {{-- Not PUT because we handle file upload with POST usually, but form-modal might handle method override. Let's use POST and generic update logic --}}
    data-redirect="false" {{-- Don't redirect, just reload table --}}
>
    <input type="hidden" name="target_unit_id" value="{{ $targetUnitId }}">

    {{-- Indikator Info (Read Only) --}}
    <div class="mb-3">
        <label class="form-label">Indikator</label>
        <div class="form-control-plaintext">{{ $indikator->indikator }}</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Target</label>
        <div class="form-control-plaintext font-weight-bold">{{ $pivot->target ?? '(Belum ditetapkan)' }}</div>
    </div>

    {{-- Inputs --}}
    <div class="mb-3">
        <x-tabler.form-input 
            name="ed_capaian" 
            label="Capaian" 
            placeholder="Isi capaian misal: 100%, 5 Dokumen, dsb." 
            :value="$pivot->ed_capaian ?? ''" 
            required="true" 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-textarea 
            name="ed_analisis" 
            label="Analisis Capaian" 
            placeholder="Jelaskan analisis capaian, kendala, atau upaya tindak lanjut." 
            :value="$pivot->ed_analisis ?? ''" 
            rows="4" 
            required="true" 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            name="ed_attachment" 
            label="Bukti Dukung (File)" 
            type="file" 
            accept=".pdf,.doc,.docx,.jpg,.png"
            helper="Maksimal 5MB. Format: PDF, Word, Gambar."
        />
    </div>
    
    @if(isset($pivot) && $pivot->ed_attachment)
        <div class="mt-2">
            <small class="text-muted">File saat ini:</small>
            <a href="{{ Storage::url($pivot->ed_attachment) }}" target="_blank" class="d-block text-truncate">{{ basename($pivot->ed_attachment) }}</a>
        </div>
    @endif

</x-tabler.form-modal>
