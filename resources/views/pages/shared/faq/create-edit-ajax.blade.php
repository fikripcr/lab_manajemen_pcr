<x-tabler.form-modal
    :title="$faq->exists ? 'Edit FAQ' : 'Tambah FAQ'"
    :route="$faq->exists ? route('shared.faq.update', $faq->encrypted_faq_id) : route('shared.faq.store')"
    :method="$faq->exists ? 'PUT' : 'POST'"
>
    <x-tabler.flash-message />
    
    <x-tabler.form-input 
        name="question" 
        label="Pertanyaan" 
        value="{{ $faq->question }}"
        required="true"
    />

    <x-tabler.form-textarea 
        name="answer" 
        id="answer"
        label="Jawaban" 
        height="300"
        required="true"
    >{{ $faq->answer }}</x-tabler.form-textarea>

    <div class="mb-3">
        <x-tabler.form-input 
            name="category" 
            label="Kategori (Opsional)" 
            value="{{ $faq->category }}"
        />
    </div>

    <div class="mt-3">
        <label class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" {{ $faq->exists ? ($faq->is_active ? 'checked' : '') : 'checked' }}>
            <span class="form-check-label">Aktifkan FAQ</span>
        </label>
    </div>
</x-tabler.form-modal>

@push('js')
<script>
    if (window.loadHugeRTE) {
        window.loadHugeRTE('#answer', { height: 300 });
    }
</script>
@endpush
